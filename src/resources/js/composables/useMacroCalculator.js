import { ref, computed } from 'vue';

/**
 * Calcula macros basado en parámetros (función pura)
 * @param {Object} params - Parámetros de cálculo
 * @returns {Object} - Macros calculados
 */
export function calculateMacros({ weight, height, age, gender, activityLevel, goal }) {
  // Validación básica
  if (!weight || !height || !age) return null;

  // Fórmula de Mifflin-St Jeor
  let bmr = (10 * weight) + (6.25 * height) - (5 * age);
  bmr = gender === 'male' ? bmr + 5 : bmr - 161;

  const tdee = bmr * activityLevel;

  let targetCalories = tdee;
  if (goal === 'cut') targetCalories -= 500;
  if (goal === 'grow') targetCalories += 300;

  return {
    calories: Math.round(targetCalories),
    protein: Math.round((targetCalories * 0.30) / 4),
    carbs: Math.round((targetCalories * 0.40) / 4),
    fat: Math.round((targetCalories * 0.30) / 9)
  };
}

/**
 * Composable reactivo para cálculo de macros
 */
export function useMacroCalculator() {
  const gender = ref('male');
  const weight = ref(0);
  const height = ref(0);
  const age = ref(0);
  const activityLevel = ref(1.2);
  const goal = ref('maintain');

  const activityFactors = [
    { title: 'Sedentario (Oficina/Sin ejercicio)', value: 1.2 },
    { title: 'Ligero (Ejercicio 1-3 días/sem)', value: 1.375 },
    { title: 'Moderado (Ejercicio 3-5 días/sem)', value: 1.55 },
    { title: 'Activo (Ejercicio 6-7 días/sem)', value: 1.725 },
    { title: 'Muy Activo (Atleta/Trabajo físico)', value: 1.9 }
  ];

  const calculatedResults = computed(() => {
    return calculateMacros({
      weight: weight.value,
      height: height.value,
      age: age.value,
      gender: gender.value,
      activityLevel: activityLevel.value,
      goal: goal.value
    });
  });

  return {
    gender,
    weight,
    height,
    age,
    activityLevel,
    goal,
    activityFactors,
    calculatedResults
  };
}
