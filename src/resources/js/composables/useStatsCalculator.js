export function useStatsCalculator() {
  
  const getNutrientValue = (log, metric) => {
    const item = log.ingredient || log.recipe;
    if (!item) return 0;

    const isIngredient = !!log.ingredient;

    const logMapping = {
      kcal: isIngredient ? 'kcal' : 'total_kcal',
      protein: isIngredient ? 'prot' : 'total_prot',
      carbs: isIngredient ? 'carb' : 'total_carb',
      fat: isIngredient ? 'fat' : 'total_fat'
    };

    const field = logMapping[metric];
    return parseFloat(item[field] || 0);
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

    const targetDate = new Date(dateStr);
    
    // ORDENAR: Más nuevos primero
    const sortedGoals = [...allGoals].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

    // BUSCAR META VIGENTE
    const applicableGoal = sortedGoals.find(goal => {
      const goalDate = new Date(goal.created_at);
      return goalDate <= targetDate.setHours(23, 59, 59);
    });

    if (applicableGoal) {
      const value = applicableGoal[metric] ?? applicableGoal['prot'] ?? applicableGoal['carb'] ?? 0;
      return parseFloat(value);
    }

    return null;
  };

  return { aggregateLogsByDay, getTargetForDate };
}
