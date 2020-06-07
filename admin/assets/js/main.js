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
			var html = `<div class="sortables_field_wrapper meng_quiz_single_field">
							<div class="meng_counter">${counter1}</div>
							<input type="text" name="meng_sortables[${counter1}][static]" class="meng_sortables_static_input" value="">
							<input type="text" name="meng_sortables[${counter1}][dynamic]" class="meng_sortables_dynamic_input" value="" placeholder="Enter value for draggable field...">
						</div>`;
			$(".meng_sortables").append(html);
		});
		// var counter2 = $(".mcqs-cloze>*").length;
		// $("span#meng_mcqs_cloze_add_btn").on("click", (e) => {
		// 	counter2++;
		// 	var html = `<div class="meng_mcqs_cloze_wrapper meng_quiz_single_field">
		// 					<div class="meng_counter">${counter2}</div>
		// 					<input name="meng_mcqs_cloze[${counter}]"`
		// });
	});
})(jQuery);
