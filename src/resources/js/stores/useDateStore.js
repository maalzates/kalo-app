import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useDateStore = defineStore('dateStore', () => {
  const selectedDate = ref(new Date());

  const setSelectedDate = (newDate) => {
    selectedDate.value = newDate;
  };

  // ex: 10 de diciembre de 2025
  const fullDate = computed(() => {
    return selectedDate.value.toLocaleDateString('es-ES', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    });
  });

  // dd-mm-yyyy
  const formattedDate = computed(() => {
    const d = selectedDate.value;
    const day = d.getDate().toString().padStart(2, '0');
    const month = (d.getMonth() + 1).toString().padStart(2, '0');
    const year = d.getFullYear();
    return `${day}-${month}-${year}`;
  });

  return {
    selectedDate,
    setSelectedDate,
    formattedDate,
    fullDate
  };
});
