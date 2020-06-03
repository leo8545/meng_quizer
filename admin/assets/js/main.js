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
		var counter1 = $(".meng_sortables>*").length;
		$("span#meng_sortable_add_btn").on("click", (e) => {
			counter1++;
			var html = `<div class="sortables_field_wrapper">
							<div>${counter1}</div>
							<input type="text" name="meng_sortables[${counter1}][static]" value="">
							<input type="text" name="meng_sortables[${counter1}][dynamic]" value="">
						</div>`;
			$(".meng_sortables").append(html);
		});
	});
})(jQuery);
