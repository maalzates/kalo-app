export const users = [
    {
      id: 1,
      name: 'Juan Dueñas',
      email: 'juan.duenas@example.com',
      birth_date: '1992-05-15',
      gender: 'male',
      height: 180,
      activity_level: 'moderate',
      goal_type: 'fat_loss',
      country_code: '+57',
      phone: '3113334567', 
      macro_calculation_mode: 'auto',
      current_targets: {
        calories: 2250,
        protein: 170,
        carbs: 240,
        fat: 65
      },
      settings: {
        theme: 'light',
        unit_system: 'metric' // metric o imperial
      },
      created_at: '2025-01-10T10:00:00Z',
      biometrics: [
        {
          id: 101,
          date: '2025-12-01',
          weight: 88.5,
          body_fat_pct: 24.5,
          waist_circumference: 98,
          notes: 'Inicio de plan, pesado en ayunas.'
        },
        {
          id: 102,
          date: '2025-12-08',
          weight: 87.2,
          body_fat_pct: null,
          waist_circumference: 97,
          notes: 'Primera semana completada.'
        },
        {
          id: 103,
          date: '2025-12-15',
          weight: 86.4,
          body_fat_pct: 23.8,
          waist_circumference: 96,
          notes: 'Segunda semana completada.'
        }
      ]
    },
    {
      id: 2,
      name: 'Manuel Perez',
      email: 'manuel.perez@example.com',
      birth_date: '1992-05-15',
      gender: 'male',
      height: 180,
      activity_level: 'moderate',
      goal_type: 'fat_loss',
      biometrics: [
        {
          id: 101,
          date: '2025-12-01',
          weight: 88.5,
          body_fat_pct: 24.5,
          waist_circumference: 98,
          notes: 'Inicio de plan, pesado en ayunas.'
        },
        {
          id: 102,
          date: '2025-12-08',
          weight: 87.2,
          body_fat_pct: null,
          waist_circumference: 97,
          notes: 'Primera semana completada.'
        }
      ]
    },
    {
      id: 3,
      name: 'Pedro Garcia',
      email: 'pedro.garcia@example.com',
      birth_date: '1992-05-15',
      gender: 'male',
      height: 180,
      activity_level: 'moderate',
      goal_type: 'fat_loss',
      biometrics: [
        {
          id: 101,
          date: '2025-12-01',
          weight: 88.5,
          body_fat_pct: 24.5,
          waist_circumference: 98,
          notes: 'Inicio de plan, pesado en ayunas.'
        }
      ]
    },
    {
      id: 4,
      name: 'Maria Lopez',
      email: 'maria.lopez@example.com',
      birth_date: '1992-05-15',
      gender: 'female',
      height: 160,
      activity_level: 'light',
      goal_type: 'maintenance',
      biometrics: [
        {
          id: 101,
          date: '2025-12-01',
          weight: 88.5,
          body_fat_pct: 24.5,
          waist_circumference: 98,
          notes: 'Inicio de plan, pesado en ayunas.'
        }
      ]
    },
    {
      id: 5,
      name: 'Carlos Rodriguez',
      email: 'carlos.rodriguez@example.com',
      birth_date: '1992-05-15',
      gender: 'male',
      height: 180,
      activity_level: 'moderate',
      goal_type: 'fat_loss',
      biometrics: [
        {
          id: 101,
          date: '2025-12-01',
          weight: 88.5,
          body_fat_pct: 24.5,
          waist_circumference: 98,
          notes: 'Inicio de plan, pesado en ayunas.'
        }
      ]
    },
    {
      id: 6,
      name: 'Ana Martinez',
      email: 'ana.martinez@example.com',
      birth_date: '1992-05-15',
      gender: 'female',
      height: 160,
      activity_level: 'light',
      goal_type: 'maintenance',
      biometrics: [
        {
          id: 101,
          date: '2025-12-01',
          weight: 88.5,
          body_fat_pct: 24.5,
          waist_circumference: 98,
          notes: 'Inicio de plan, pesado en ayunas.'
        }
      ]
    }
  ];
