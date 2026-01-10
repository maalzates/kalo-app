/**
 * Composable para manejar niveles de actividad
 * Mapea integers (1-4) a labels amigables y multipliers para cálculos
 */

export const useActivityLevel = () => {
  // Mapeo de integer a configuración completa
  const activityLevels = {
    1: {
      label: 'Sedentario',
      description: 'Poco o ningún ejercicio, trabajo de oficina',
      multiplier: 1.2
    },
    2: {
      label: 'Ligero',
      description: 'Ejercicio ligero 1-3 días por semana',
      multiplier: 1.375
    },
    3: {
      label: 'Moderado',
      description: 'Ejercicio moderado 3-5 días por semana',
      multiplier: 1.55
    },
    4: {
      label: 'Muy Activo',
      description: 'Ejercicio intenso 6-7 días por semana o trabajo físico',
      multiplier: 1.9
    }
  };

  /**
   * Obtiene el label del nivel de actividad
   * @param {number} level - Nivel de actividad (1-4)
   * @returns {string} - Label amigable
   */
  const getActivityLabel = (level) => {
    return activityLevels[level]?.label || 'Sin definir';
  };

  /**
   * Obtiene la descripción del nivel de actividad
   * @param {number} level - Nivel de actividad (1-4)
   * @returns {string} - Descripción detallada
   */
  const getActivityDescription = (level) => {
    return activityLevels[level]?.description || '';
  };

  /**
   * Obtiene el multiplicador para cálculos de TDEE
   * @param {number} level - Nivel de actividad (1-4)
   * @returns {number} - Multiplicador (1.2-1.9)
   */
  const getActivityMultiplier = (level) => {
    return activityLevels[level]?.multiplier || 1.2;
  };

  /**
   * Obtiene todas las opciones de actividad para selectores
   * @returns {Array} - Array de objetos con value, label y description
   */
  const getActivityOptions = () => {
    return Object.entries(activityLevels).map(([value, config]) => ({
      value: parseInt(value),
      label: config.label,
      description: config.description
    }));
  };

  return {
    activityLevels,
    getActivityLabel,
    getActivityDescription,
    getActivityMultiplier,
    getActivityOptions
  };
};
