<template>
    <div class="camera-wrapper bg-black rounded-xl overflow-hidden position-relative">
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
        <div class="mt-4 text-white font-weight-bold text-body-2">Analizando plato...</div>
      </v-overlay>
  
      <div class="controls-overlay pa-4 d-flex justify-center align-center">
        <v-btn
          v-if="isStreamActive && !capturedImage"
          icon="mdi-camera"
          size="72"
          color="white"
          elevation="8"
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
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue';
  import { useMealLogsStore } from '@/stores/useMealLogsStore';
  
  const emit = defineEmits(['analysis-finished']);
  const mealStore = useMealLogsStore();
  const videoPlayer = ref(null);
  const isStreamActive = ref(false);
  const capturedImage = ref(null);
  const imageBlob = ref(null);
  
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
    }
  };
  
  const takePhoto = () => {
    const video = videoPlayer.value;
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);
  
    capturedImage.value = canvas.toDataURL('image/jpeg');
    canvas.toBlob((blob) => { imageBlob.value = blob; }, 'image/jpeg', 0.8);
    stopCamera();
  };
  
  const processPhoto = async () => {
    if (!imageBlob.value) return;
    try {
      const result = await mealStore.analyzeMealImage(imageBlob.value);
      emit('analysis-finished', result);
    } catch (err) {
      console.error("Error en análisis");
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
  
  onMounted(() => startCamera());
  onBeforeUnmount(() => stopCamera());
  </script>
  
  <style scoped>
  .camera-wrapper {
    width: 100%;
    /* Mantiene la proporción 4:3 típica de cámaras móviles */
    aspect-ratio: 3 / 4; 
    max-height: 70vh;
    position: relative;
  }
  
  .camera-element {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .controls-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
    z-index: 2;
  }
  
  .shutter-button {
    border: 4px solid rgba(255,255,255,0.3) !important;
  }
  
  .max-width-mobile {
    max-width: 400px;
  }
  
  @media (min-width: 600px) {
    .camera-wrapper {
      aspect-ratio: 16 / 9; /* En escritorio es mejor panorámico */
    }
  }
  </style>
