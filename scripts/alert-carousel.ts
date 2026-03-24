declare const Splide: any;

document.addEventListener('DOMContentLoaded', () => {
	const carousels: NodeListOf<HTMLElement> = document.querySelectorAll('.alert-carousel--carousel');

	carousels.forEach((carousel: HTMLElement) => {
		new Splide(carousel, {
			type: 'loop',
			perPage: 1,
			perMove: 1,
			arrows: true,
			pagination: true,
			drag: true,
			autoplay: false,
			speed: 600,
		}).mount();
	});
});