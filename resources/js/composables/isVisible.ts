import { ref, onMounted, onUnmounted } from 'vue';

function isInViewport(element: HTMLElement, treshold: number): boolean {
	const rect = element.getBoundingClientRect();
	const windowHeight =
		window.innerHeight || document.documentElement.clientHeight;
	const windowWidth =
		window.innerWidth || document.documentElement.clientWidth;

	const vertInView =
		rect.top <= windowHeight + treshold &&
		rect.top + rect.height >= 0 - treshold;
	const horInView =
		rect.left <= windowWidth + treshold &&
		rect.left + rect.width >= 0 - treshold;

	return vertInView && horInView;
}

export function useIsVisible(
	elementRef: { value: HTMLElement | null },
	params: {
		treshold?: number;
		triggerOnce?: boolean;
	} = {}
) {
	const isVisible = ref(false);
	const { treshold = 0, triggerOnce = false } = params;

	const checkIsVisible = () => {
		if (elementRef.value) {
			isVisible.value = isInViewport(elementRef.value, treshold);

			if (triggerOnce && isVisible.value) {
				unbindListeners();
			}
		}
	};

	const bindListeners = () => {
		window.addEventListener('scroll', checkIsVisible);
		window.addEventListener('resize', checkIsVisible);
	};

	const unbindListeners = () => {
		window.removeEventListener('scroll', checkIsVisible);
		window.removeEventListener('resize', checkIsVisible);
	};

	onMounted(() => {
		bindListeners();
		checkIsVisible();
	});

	onUnmounted(() => {
		unbindListeners();
	});

	return isVisible;
}

export function useViewportPercentage(
	elementRef: { value: HTMLElement | null },
	args: { treshold?: number } = {}
) {
	const viewportPercentage = ref(0);
	const { treshold = 0 } = args;

	const calculateViewportPercentage = () => {
		const element = elementRef.value;
		if (element) {
			const rect = element.getBoundingClientRect();
			const windowHeight =
				(window.innerHeight || document.documentElement.clientHeight) *
				treshold;

			const elementHeight = rect.height;
			const visibleHeight =
				Math.min(windowHeight, Math.max(0, rect.bottom)) -
				Math.max(0, rect.top);
			const percentage = (visibleHeight / elementHeight) * 100;

			viewportPercentage.value = Math.round(percentage);
		}
	};

	const handleScroll = () => {
		calculateViewportPercentage();
	};

	onMounted(() => {
		window.addEventListener('scroll', handleScroll);
		calculateViewportPercentage();
	});

	onUnmounted(() => {
		window.removeEventListener('scroll', handleScroll);
	});

	return viewportPercentage;
}
