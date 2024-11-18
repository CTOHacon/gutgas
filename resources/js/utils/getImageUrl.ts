const getImageUrl = (imageUrl: string) => {
	return new URL(imageUrl, import.meta.url).href;
};

export default getImageUrl;
