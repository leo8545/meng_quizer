var getNextSibling = function (elem, selector) {
	// Get the next sibling element
	var sibling = elem.nextElementSibling;

	// If there's no selector, return the first sibling
	if (!selector) return sibling;

	// If the sibling matches our selector, use it
	// If not, jump to the next sibling and continue the loop
	while (sibling) {
		if (sibling.matches(selector)) return sibling;
		sibling = sibling.nextElementSibling;
	}
};
var getPreviousSibling = function (elem, selector) {
	// Get the next sibling element
	var sibling = elem.previousElementSibling;

	// If there's no selector, return the first sibling
	if (!selector) return sibling;

	// If the sibling matches our selector, use it
	// If not, jump to the next sibling and continue the loop
	while (sibling) {
		if (sibling.matches(selector)) return sibling;
		sibling = sibling.previousElementSibling;
	}
};
(function ($) {
	$(document).ready(() => {
		$(".meng-slider > *:first-child").addClass("meng-active-slide");
		$(".meng-slider > *").addClass("meng-slide");
		$(".meng-slide").attr("data-meng_slide", "hey");
		$.each($(".meng-slide"), (index, slide) => {
			slide.setAttribute("data-meng_slide", index);
		});
		var btnWrapper = `<div class="meng-slider-btns">
									<div class="meng-btn-prev">Previous</div>
									<div class="meng-btn-next">Next</div>
								</div>`;
		$(".meng-slider").append(btnWrapper);
		var slides = document.querySelectorAll(".meng-slider .meng-slide");
		// Next
		$(".meng-btn-next").on("click", (e) => {
			var activeSlide = document.querySelector(".meng-slide.meng-active-slide");
			if (activeSlide && getNextSibling(activeSlide, "li.meng-slide")) {
				activeSlide.nextElementSibling.classList.add("meng-active-slide");
				activeSlide.classList.remove("meng-active-slide");
			}
		});
		// Previous
		$(".meng-btn-prev").on("click", (e) => {
			var activeSlide = document.querySelector(".meng-slide.meng-active-slide");
			if (activeSlide && getPreviousSibling(activeSlide, "li.meng-slide")) {
				activeSlide.classList.remove("meng-active-slide");
				getPreviousSibling(activeSlide, "li.meng-slide").classList.add(
					"meng-active-slide"
				);
			}
		});
		// Time
		var timerWrapper = `<div class="meng-timer"></div>`;
		var startTime = new Date().getTime();
		setInterval(() => {
			var endTime = new Date().getTime();
			var diff = endTime - startTime;
			var secs = Math.floor(diff / 1000);
			if (secs > 2) {
				$(".meng-timer").css("color", "#f00");
				clearInterval();
			}
			$(".meng-timer").text(secs + " sec");
		}, 1000);
		$(".meng-slider").prepend(timerWrapper);
		// Attempted counter
		var attemptedCounterWrapper = `<div class="meng-attempted-counter-wrapper">
													<div>Attempted: <span class="meng-att-count">0</span>/<span class="meng-att-total">${slides.length}</span><div>
												<div>`;
		$(".meng-slider").prepend(attemptedCounterWrapper);
		var counter = new Set();
		$(`.meng-slider .meng-slide input[type="radio"]`).on("click", (e) => {
			counter.add(
				e.target.closest(".meng-slide").getAttribute("data-meng_slide")
			);
			$(".meng-att-count").text(counter.size);
			// if (counter.size === slides.length) {
			// 	var endTime = new Date().getTime();
			// 	var diff = endTime - startTime;
			// 	console.log(endTime, startTime, diff / 1000);
			// }
		});
	});
})(jQuery);
