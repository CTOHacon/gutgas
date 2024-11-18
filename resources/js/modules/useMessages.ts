import { IMessage } from '@/types/IMessage';
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const page = usePage();
const messages = ref<IMessage[]>([]);
watch(
	() => page.props?.message,
	(message: IMessage) => {
		if (message) {
			addMessage(message);
		}
	},
	{ immediate: true }
);

const addMessage = (message: IMessage) => {
	messages.value.push(message);
	setTimeout(() => {
		messages.value = messages.value.filter(m => m.text !== message.text);
	}, 4000);
};

const useMessages = () => {
	return {
		messages,
		addMessage
	};
};

export default useMessages;
