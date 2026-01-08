<template>
    <!-- Desktop: Show button to open camera modal -->
    <div v-if="$vuetify.display.mdAndUp">
      <v-card rounded="xl" class="pa-8 text-center" border="sm">
        <v-icon size="80" color="deep-purple-accent-4" class="mb-4">mdi-camera-outline</v-icon>
        <h3 class="text-h6 font-weight-bold mb-2">Analizar con Cámara</h3>
        <p class="text-body-2 text-grey-darken-1 mb-4">
          Toma una foto de tu plato para obtener un análisis automático de macros
        </p>
        <v-btn
          color="deep-purple-accent-4"
          variant="flat"
          rounded="pill"
          size="large"
          prepend-icon="mdi-camera"
          class="font-weight-bold mb-3"
          @click="openCameraModal"
        >
          Abrir Cámara
        </v-btn>
        <v-btn
          color="deep-purple-accent-4"
          variant="outlined"
          rounded="pill"
          size="large"
          prepend-icon="mdi-folder-image"
          class="font-weight-bold"
          @click="openFileUpload"
        >
          Subir Foto
        </v-btn>
      </v-card>

      <!-- Camera Modal for Desktop -->
      <v-dialog v-model="showCameraModal" max-width="900px">
        <v-card class="camera-modal-card">
          <v-card-text class="pa-0">
            <div class="camera-wrapper bg-black position-relative" style="height: 600px;">
              <div class="instruction-overlay pa-4 text-center">
                <div class="text-white font-weight-bold text-body-2 shadow-text">
                  Encuadra tu plato para analizar macros
                </div>
              </div>

              <video
                v-show="!capturedImage"
                ref="videoPlayer"
                autoplay
                playsinline
                class="camera-element"
              ></video>

              <v-img
                v-if="capturedImage"
                :src="capturedImage"
                class="camera-element"
                cover
              ></v-img>

              <v-overlay v-model="mealStore.isAnalyzing" contained class="align-center justify-center text-center px-4">
                <v-progress-circular indeterminate color="white" size="64"></v-progress-circular>
                <div class="mt-4 text-white font-weight-bold text-body-1 shadow-text">Analizando composición...</div>
              </v-overlay>

              <div class="controls-overlay pa-6 d-flex justify-center align-center ga-3">
                <v-btn
                  icon="mdi-close"
                  size="small"
                  color="white"
                  variant="text"
                  class="close-button"
                  @click="closeCameraModal"
                ></v-btn>

                <v-btn
                  v-if="isStreamActive && !capturedImage"
                  icon="mdi-camera"
                  size="80"
                  color="white"
                  elevation="12"
                  class="shutter-button"
                  @click="takePhoto"
                ></v-btn>

                <v-btn
                  v-if="isStreamActive && !capturedImage"
                  icon="mdi-folder-image"
                  size="small"
                  color="white"
                  variant="text"
                  class="upload-button"
                  @click="openFileUpload"
                ></v-btn>

                <div v-if="capturedImage && !mealStore.isAnalyzing" class="d-flex ga-3 w-100 max-width-mobile">
                  <v-btn
                    color="white"
                    variant="tonal"
                    rounded="pill"
                    size="large"
                    class="flex-grow-1 font-weight-bold"
                    @click="resetCamera"
                  >
                    Repetir
                  </v-btn>
                  <v-btn
                    color="deep-purple-accent-4"
                    variant="flat"
                    rounded="pill"
                    size="large"
                    class="flex-grow-1 font-weight-bold"
                    @click="processPhoto"
                  >
                    Analizar
                  </v-btn>
                </div>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
    </div>

    <!-- Mobile: Fullscreen camera view -->
    <div v-else class="camera-wrapper bg-black rounded-xl overflow-hidden position-relative">
      <div class="instruction-overlay pa-4 text-center">
        <div class="text-white font-weight-bold text-body-2 shadow-text">
          Encuadra tu plato para analizar macros
        </div>
      </div>

      <video
        v-show="!capturedImage"
        ref="videoPlayer"
        autoplay
        playsinline
        class="camera-element"
      ></video>

      <v-img
        v-if="capturedImage"
        :src="capturedImage"
        class="camera-element"
        cover
      ></v-img>

      <v-overlay v-model="mealStore.isAnalyzing" contained class="align-center justify-center text-center px-4">
        <v-progress-circular indeterminate color="white" size="64"></v-progress-circular>
        <div class="mt-4 text-white font-weight-bold text-body-1 shadow-text">Analizando composición...</div>
      </v-overlay>

      <div class="controls-overlay pa-6 d-flex justify-center align-center ga-3">
        <v-btn
          v-if="isStreamActive && !capturedImage"
          icon="mdi-folder-image"
          size="small"
          color="white"
          variant="text"
          style="position: absolute; top: 16px; right: 16px;"
          @click="openFileUpload"
        ></v-btn>

        <v-btn
          v-if="isStreamActive && !capturedImage"
          icon="mdi-camera"
          size="80"
          color="white"
          elevation="12"
          class="shutter-button"
          @click="takePhoto"
        ></v-btn>

        <div v-if="capturedImage && !mealStore.isAnalyzing" class="d-flex ga-3 w-100 max-width-mobile">
          <v-btn
            color="white"
            variant="tonal"
            rounded="pill"
            size="large"
            class="flex-grow-1 font-weight-bold"
            @click="resetCamera"
          >
            Repetir
          </v-btn>
          <v-btn
            color="deep-purple-accent-4"
            variant="flat"
            rounded="pill"
            size="large"
            class="flex-grow-1 font-weight-bold"
            @click="processPhoto"
          >
            Analizar
          </v-btn>
        </div>
      </div>
    </div>

    <!-- Hidden file input -->
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      style="display: none"
      @change="handleFileUpload"
    />
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
  import { useMealLogsStore } from '@/stores/useMealLogsStore';
  import { useDisplay } from 'vuetify';
  import { useToast } from 'vue-toastification';

  const emit = defineEmits(['analysis-finished']);
  const mealStore = useMealLogsStore();
  const { mdAndUp } = useDisplay();
  const toast = useToast();

  const videoPlayer = ref(null);
  const isStreamActive = ref(false);
  const capturedImage = ref(null);
  const imageBlob = ref(null);
  const showCameraModal = ref(false);
  const fileInput = ref(null);

  const startCamera = async () => {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } },
        audio: false
      });
      if (videoPlayer.value) {
        videoPlayer.value.srcObject = stream;
        isStreamActive.value = true;
      }
    } catch (err) {
      console.error("Error acceso cámara:", err);
      toast.error('No se pudo acceder a la cámara. Verifica los permisos.');
    }
  };

  const takePhoto = () => {
    const video = videoPlayer.value;
    const canvas = document.createElement('canvas');

    // --- Lógica de Redimensionado ---
    const MAX_WIDTH = 800; // Suficiente para que la IA vea texturas de comida
    let width = video.videoWidth;
    let height = video.videoHeight;

    if (width > MAX_WIDTH) {
        height = (MAX_WIDTH / width) * height;
        width = MAX_WIDTH;
    }

    canvas.width = width;
    canvas.height = height;
    // --------------------------------

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, width, height);

    // Bajamos la calidad a 0.7 (70%). Visualmente es casi igual, pero pesa la mitad.
    capturedImage.value = canvas.toDataURL('image/jpeg', 0.7);
    canvas.toBlob((blob) => {
        imageBlob.value = blob;
    }, 'image/jpeg', 0.7);

    stopCamera();
  };

  const processPhoto = async () => {
    if (!imageBlob.value) return;
    try {
      const result = await mealStore.analyzeMealImage(imageBlob.value);
      if (result) {
        emit('analysis-finished', result);
        closeCameraModal();
      }
    } catch (err) {
      console.error("Error en análisis:", err);
      toast.error('Error al analizar la imagen. Intenta de nuevo.');
    }
  };

  const stopCamera = () => {
    if (videoPlayer.value?.srcObject) {
      videoPlayer.value.srcObject.getTracks().forEach(t => t.stop());
      isStreamActive.value = false;
    }
  };

  const resetCamera = () => {
    capturedImage.value = null;
    imageBlob.value = null;
    startCamera();
  };

  const openCameraModal = () => {
    showCameraModal.value = true;
    // Wait for modal to render before starting camera
    setTimeout(() => startCamera(), 100);
  };

  const closeCameraModal = () => {
    stopCamera();
    showCameraModal.value = false;
    capturedImage.value = null;
    imageBlob.value = null;
  };

  const openFileUpload = () => {
    fileInput.value?.click();
  };

  const handleFileUpload = async (event) => {
    const file = event.target.files?.[0];
    if (!file) return;

    try {
      // Validate file type
      if (!file.type.startsWith('image/')) {
        toast.error('Por favor selecciona una imagen válida');
        event.target.value = '';
        return;
      }

      // Read file as data URL for preview
      const reader = new FileReader();
      reader.onload = (e) => {
        capturedImage.value = e.target.result;
      };
      reader.readAsDataURL(file);

      // Process file as blob for upload
      const img = new Image();
      img.onload = () => {
        const canvas = document.createElement('canvas');

        // Resize logic
        const MAX_WIDTH = 800;
        let width = img.width;
        let height = img.height;

        if (width > MAX_WIDTH) {
          height = (MAX_WIDTH / width) * height;
          width = MAX_WIDTH;
        }

        canvas.width = width;
        canvas.height = height;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, width, height);

        canvas.toBlob((blob) => {
          imageBlob.value = blob;
        }, 'image/jpeg', 0.7);
      };

      img.onerror = () => {
        toast.error('Error al cargar la imagen. Intenta con otra.');
      };

      img.src = URL.createObjectURL(file);

      // Stop camera if it's running
      stopCamera();

      // Reset file input
      event.target.value = '';
    } catch (err) {
      console.error("Error procesando archivo:", err);
      toast.error('Error al procesar la imagen. Intenta de nuevo.');
      event.target.value = '';
    }
  };

  // On mobile, start camera on mount
  onMounted(() => {
    if (!mdAndUp.value) {
      startCamera();
    }
  });

  onBeforeUnmount(() => stopCamera());

  // Watch for modal close to cleanup
  watch(showCameraModal, (newVal) => {
    if (!newVal) {
      stopCamera();
      capturedImage.value = null;
      imageBlob.value = null;
    }
  });
  </script>
  
  <style scoped>
  .camera-wrapper {
    width: 100%;
    aspect-ratio: 3 / 4;
    max-height: 65vh;
    position: relative;
  }

  .camera-element {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .instruction-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.7), transparent);
    z-index: 2;
  }

  .controls-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    z-index: 2;
  }

  .shutter-button {
    border: 6px solid rgba(255,255,255,0.3) !important;
  }

  .shadow-text {
    text-shadow: 0px 2px 4px rgba(0,0,0,0.8);
  }

  .max-width-mobile {
    max-width: 400px;
  }

  .close-button {
    position: absolute;
    top: 16px;
    left: 16px;
  }

  .upload-button {
    position: absolute;
    top: 16px;
    right: 16px;
  }

  .camera-modal-card {
    overflow: hidden;
  }

  @media (min-width: 600px) {
    .camera-wrapper {
      aspect-ratio: 16 / 9;
    }
  }
  </style>
