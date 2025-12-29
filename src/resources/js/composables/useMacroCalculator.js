import { ref, computed } from 'vue';

export function useMacroCalculator() {
  const gender = ref('male');
  const weight = ref(0);
  const height = ref(0);
  const age = ref(0);
  const activityLevel = ref(1.2);
  const goal = ref('maintenance');

  const activityFactors = [
    { title: 'Sedentario (Oficina/Sin ejercicio)', value: 1.2 },
    { title: 'Ligero (Ejercicio 1-3 días/sem)', value: 1.375 },
    { title: 'Moderado (Ejercicio 3-5 días/sem)', value: 1.55 },
    { title: 'Activo (Ejercicio 6-7 días/sem)', value: 1.725 },
    { title: 'Muy Activo (Atleta/Trabajo físico)', value: 1.9 }
  ];

  const calculatedResults = computed(() => {
    // Validación básica para evitar cálculos con ceros
    if (!weight.value || !height.value || !age.value) return null;

    // Fórmula de Mifflin-St Jeor
    // Hombres: (10 × peso) + (6.25 × altura) - (5 × edad) + 5
    // Mujeres: (10 × peso) + (6.25 × altura) - (5 × edad) - 161
    let bmr = (10 * weight.value) + (6.25 * height.value) - (5 * age.value);
    bmr = gender.value === 'male' ? bmr + 5 : bmr - 161;

    const tdee = bmr * activityLevel.value;

    let targetCalories = tdee;
    if (goal.value === 'loss') targetCalories -= 500;
    if (goal.value === 'gain') targetCalories += 300;

    return {
      calories: Math.round(targetCalories),
      protein: Math.round((targetCalories * 0.30) / 4),
      carbs: Math.round((targetCalories * 0.40) / 4),
      fat: Math.round((targetCalories * 0.30) / 9)
    };
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
