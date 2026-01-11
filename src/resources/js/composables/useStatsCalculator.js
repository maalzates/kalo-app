export function useStatsCalculator() {
  
  const getNutrientValue = (log, metric) => {
    // Si tiene ingredient o recipe, usar la lógica tradicional
    const item = log.ingredient || log.recipe;
    if (item) {
      const isIngredient = !!log.ingredient;
      const logMapping = {
        kcal: isIngredient ? 'kcal' : 'total_kcal',
        protein: isIngredient ? 'prot' : 'total_prot',
        carbs: isIngredient ? 'carb' : 'total_carb',
        fat: isIngredient ? 'fat' : 'total_fat'
      };
      const field = logMapping[metric];
      const baseValue = parseFloat(item[field] || 0);
      
      const quantity = parseFloat(log.quantity) || 0;
      if (quantity === 0) return 0;
      
      const baseAmount = isIngredient 
        ? (parseFloat(item.amount) || 100)
        : (parseFloat(item.servings) || 1);
      
      if (baseAmount === 0) return 0;
      const factor = quantity / baseAmount;
      return baseValue * factor;
    }

    // Si tiene ai_data, extraer valores de ahí
    if (log.ai_data && typeof log.ai_data === 'object') {
      const aiData = log.ai_data;
      const quantity = parseFloat(log.quantity) || 0;
      if (quantity === 0) return 0;

      const aiMapping = {
        kcal: 'kcal',
        protein: 'prot',
        carbs: 'carb',
        fat: 'fat'
      };

      const field = aiMapping[metric];
      const baseValue = parseFloat(aiData[field] || 0);

      if (aiData.type === 'ingredient') {
        const baseAmount = parseFloat(aiData.amount) || 100;
        if (baseAmount === 0) return 0;
        const factor = quantity / baseAmount;
        return baseValue * factor;
      } else if (aiData.type === 'recipe') {
        const servings = parseFloat(aiData.servings) || 1;
        if (servings === 0) return 0;
        const factor = quantity / servings;
        return baseValue * factor;
      }
    }

    return 0;
  };

  const aggregateLogsByDay = (logs) => {
    if (!Array.isArray(logs)) return {};
    return logs.reduce((acc, log) => {
      const rawDate = log.logged_at || log.created_at;
      if (!rawDate) return acc;
      const date = rawDate.split('T')[0];
      if (!acc[date]) acc[date] = { kcal: 0, protein: 0, carbs: 0, fat: 0 };
      
      acc[date].kcal += getNutrientValue(log, 'kcal');
      acc[date].protein += getNutrientValue(log, 'protein');
      acc[date].carbs += getNutrientValue(log, 'carbs');
      acc[date].fat += getNutrientValue(log, 'fat');
      return acc;
    }, {});
  };

  const getTargetForDate = (dateStr, allGoals, metric) => {
    if (!allGoals || !allGoals.length) return null;

    // ✅ FIX: Parsear como fecha local en lugar de UTC
    const [year, month, day] = dateStr.split('-');
    const targetDate = new Date(year, month - 1, day, 23, 59, 59);
    
    // ORDENAR: Más nuevos primero
    const sortedGoals = [...allGoals].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

    // BUSCAR META VIGENTE
    const applicableGoal = sortedGoals.find(goal => {
      const goalDate = new Date(goal.created_at);
      return goalDate <= targetDate;
    });

    if (applicableGoal) {
      const value = applicableGoal[metric] ?? applicableGoal['prot'] ?? applicableGoal['carb'] ?? 0;
      return parseFloat(value);
    }

    return null;
  };

  /**
   * Calcula el score de adherencia (0-100) para cada día
   * Score = qué tan cerca estuviste de tus objetivos
   */
  const calculateAdherence = (logs, macroGoals) => {
    const dailyData = aggregateLogsByDay(logs);
    const adherenceByDay = {};

    Object.keys(dailyData).forEach(date => {
      const consumed = dailyData[date];
      const targets = {
        kcal: getTargetForDate(date, macroGoals, 'kcal'),
        protein: getTargetForDate(date, macroGoals, 'prot'),
        carbs: getTargetForDate(date, macroGoals, 'carb'),
        fat: getTargetForDate(date, macroGoals, 'fat')
      };

      // Si no hay targets ese día, score = 0
      if (!targets.kcal) {
        adherenceByDay[date] = 0;
        return;
      }

      // Calcular % de adherencia por métrica (penaliza tanto exceso como déficit)
      const kcalScore = Math.max(0, 100 - Math.abs((consumed.kcal - targets.kcal) / targets.kcal * 100));
      const proteinScore = targets.protein ? Math.max(0, 100 - Math.abs((consumed.protein - targets.protein) / targets.protein * 100)) : 100;
      const carbsScore = targets.carbs ? Math.max(0, 100 - Math.abs((consumed.carbs - targets.carbs) / targets.carbs * 100)) : 100;
      const fatScore = targets.fat ? Math.max(0, 100 - Math.abs((consumed.fat - targets.fat) / targets.fat * 100)) : 100;

      // Promedio ponderado (calorías cuenta más)
      adherenceByDay[date] = (kcalScore * 0.4 + proteinScore * 0.2 + carbsScore * 0.2 + fatScore * 0.2);
    });

    return adherenceByDay;
  };

  /**
   * Calcula composición corporal (masa grasa vs masa magra) a partir de biometrics
   */
  const calculateBodyComposition = (biometrics) => {
    if (!Array.isArray(biometrics)) return [];

    return biometrics
      .filter(b => b.weight && b.fat_percentage)
      .map(b => {
        const weight = parseFloat(b.weight);
        const fatPercentage = parseFloat(b.fat_percentage);
        const fatMass = weight * (fatPercentage / 100);
        const leanMass = weight - fatMass;

        return {
          date: b.measured_at,
          weight,
          fatMass: parseFloat(fatMass.toFixed(2)),
          leanMass: parseFloat(leanMass.toFixed(2))
        };
      })
      .sort((a, b) => new Date(a.date) - new Date(b.date));
  };

  return {
    aggregateLogsByDay,
    getTargetForDate,
    calculateAdherence,
    calculateBodyComposition
  };
}
