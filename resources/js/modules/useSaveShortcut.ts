import { ref, onMounted, onUnmounted, watchEffect } from 'vue';

export function useSaveShortcut(callback: () => void) {
	const isSaveKeyPressed = ref(false);

	// Event listener to handle keydown events
	function handleKeyDown(event: KeyboardEvent) {
		if ((event.ctrlKey || event.metaKey) && event.key === 's') {
			event.preventDefault(); // Prevent the default browser save action
			isSaveKeyPressed.value = true;
		}
	}

	// Event listener to handle keyup events
	function handleKeyUp(event: KeyboardEvent) {
		if ((event.ctrlKey || event.metaKey) && event.key === 's') {
			event.preventDefault(); // Prevent the default browser save action
			isSaveKeyPressed.value = false;
			callback();
		}
	}

	onMounted(() => {
		// Add the event listeners when the component is mounted
		window.addEventListener('keydown', handleKeyDown);
		window.addEventListener('keyup', handleKeyUp);
	});

	onUnmounted(() => {
		// Remove the event listeners when the component is unmounted
		window.removeEventListener('keydown', handleKeyDown);
		window.removeEventListener('keyup', handleKeyUp);
	});

	// Watch for changes to the `isSaveKeyPressed` ref and run the effect whenever it changes
	watchEffect(() => {
		if (isSaveKeyPressed.value) {
			// Do something while the Ctrl/Command + S is held down (optional)
		}
	});
}
