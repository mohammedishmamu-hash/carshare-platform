const { createApp, ref, onMounted } = Vue;

createApp({
    setup() {
        const vehicles = ref([]);
        const loading  = ref(true);
        const error    = ref(null);

        const fetchVehicles = async () => {
            try {
                const response = await fetch('/api/vehicles');

                // Handle non-200 HTTP responses
                if (!response.ok) {
                    throw new Error('Server returned an error. Please try again later.');
                }

                const json = await response.json();

                // Handle unexpected response shape
                if (!json.data || !Array.isArray(json.data)) {
                    throw new Error('Unexpected response from server.');
                }

                vehicles.value = json.data;

            } catch (e) {
                error.value = e.message || 'Something went wrong. Please try again later.';
            } finally {
                // Always turn off loading, whether success or failure
                loading.value = false;
            }
        };

        onMounted(fetchVehicles);

        return { vehicles, loading, error };
    }

}).mount('#app');