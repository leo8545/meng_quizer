(function ($) {
	$(document).ready(() => {
		let counter = $(".mcqs>*").length;
		$("span#meng_mcqs_add_btn").on("click", (e) => {
			counter++;
			let html = `<div>${counter}</div>`;
			html += `<input name="meng_mcqs[${counter}][statement]" class="mcqs_statement" placeholder="Enter the mcq's statement" />`;
			html += `<input name="meng_mcqs[${counter}][options]" class="mcqs_options" placeholder="Enter mcqs options here separated by '|'" />`;
			$(".mcqs").append(`<div id="mcq-${counter}">${html}</div>`);
		});
	});
})(jQuery);
