import { ref } from 'vue';

const isNavigationModalOpened = ref(false);
const toggleNavigationModal = () => {
	isNavigationModalOpened.value = !isNavigationModalOpened.value;

	// toggle lock html scroll
	if (isNavigationModalOpened.value) {
		document.documentElement.classList.add('lock');
	} else {
		document.documentElement.classList.remove('lock');
	}
};

export default function useNavigationModal() {
	return {
		isNavigationModalOpened,
		toggleNavigationModal
	};
}
