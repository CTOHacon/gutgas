<template>
	<div class="g-layout">
		<div class="g-col-3 g-tablet-col-12">
			<div
				class="filters"
				:style="{
					top: headerHeight + 8 + 'px'
				}"
			>
				<div class="filters__inner">
					<slot name="filters"></slot>
				</div>
			</div>
		</div>
		<div class="g-col-9 g-tablet-col-12">
			<slot name="productsGroups"></slot>
			<div class="g-layout products-layout">
				<slot name="products"></slot>
			</div>
		</div>
		<slot name="pagination"></slot>
	</div>
</template>

<script setup lang="ts">
import useHeaderElement from '@/composables/headerElement';

const { headerHeight } = useHeaderElement();
</script>

<style scoped lang="scss">
@import '@/styles/variables.scss';

:deep(.products-layout) {
	gap: 0;
	& > * {
		margin-bottom: -1px;
		margin-right: -1px;
	}
}
.filters {
	position: sticky;
	max-height: calc(100vh - 160px);
	display: flex;
	justify-content: stretch;
	&__inner {
		flex: 1;
	}

	@media (max-width: $tablet-width) {
		position: relative;
		max-height: none;
		margin-bottom: 24px;
	}
}
.banner {
	border: 1px solid #000;
}
</style>
