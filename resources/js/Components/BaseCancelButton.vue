<template>
	<button class="cancel-button">
		<svg
			class="icon"
			width="25"
			height="24"
			viewBox="0 0 25 24"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
		>
			<circle
				cx="12.5"
				cy="12"
				r="11.5"
				stroke="#828282"
				fill="#202020"
				stroke-opacity="0.12"
			/>
			<path
				d="M17.5 7L7.5 17"
				stroke="#828282"
				stroke-linecap="round"
				stroke-linejoin="round"
			/>
			<path
				d="M7.5 7L17.5 17"
				stroke="#828282"
				stroke-linecap="round"
				stroke-linejoin="round"
			/>
		</svg>

		<span class="text lh-100">
			<slot></slot>
		</span>
	</button>
</template>

<script setup lang="ts"></script>

<style scoped lang="scss">
.cancel-button {
	display: flex;
	align-items: center;
	transition: color 0.2s ease-in-out;
	color: #e0e0e0;
	line-height: 0;
	&:active {
		color: #f24942;
	}
}
.icon {
	margin-right: 6px;
	width: 24px;
	height: 24px;
}
.icon circle {
	transition: stroke 0.2s ease-in-out, stroke-opacity 0.2s ease-in-out;
}
.cancel-button:hover .icon circle {
	stroke: #fff;
	stroke-opacity: 1;
}
.cancel-button:active .icon circle {
	stroke: #f24942;
	stroke-opacity: 1;
}
.icon path {
	transition: stroke 0.2s ease-in-out;
}
.cancel-button:hover .icon path {
	stroke: #fff;
}
.cancel-button:active .icon path {
	stroke: #f24942;
}

.text {
	font-size: 1em;
}
</style>
