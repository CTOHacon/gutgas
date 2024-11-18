import { ref, watch } from 'vue';

const headerElementRef = ref<HTMLElement | null>(null);
const headerHeight = ref(0);

watch(headerElementRef, () => {
	updateHeaderHeight();
});

const updateHeaderHeight = () => {
	if (headerElementRef.value)
		headerHeight.value = headerElementRef.value.offsetHeight;
};

export default function useHeaderElement() {
	return { headerElementRef, headerHeight, updateHeaderHeight };
}
