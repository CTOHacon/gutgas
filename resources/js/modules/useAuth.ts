import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const user = computed(() => page.props.auth.user);
const permissions = computed(() => page.props.auth.permissions);

const hasPermissions = (permission: string) => {
	if (user.value?.roles.find(role => role.name === 'super')) return true;

	permissions.value.forEach(availablePermission => {
		if (availablePermission === permission) return true;
	});

	const regexes = permissions.value.map(perm => {
		let wildcardPerm = perm.replace(/\./g, '\\.');
		wildcardPerm = wildcardPerm.replace(/\*/g, '.*');
		return new RegExp(`^${wildcardPerm}$`);
	});

	return regexes.some(regex => regex.test(permission));
};

export default function useAuth() {
	return {
		hasPermissions,
		permissions,
		user
	};
}
