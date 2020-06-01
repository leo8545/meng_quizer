(function ($) {
	$(document).ready(function () {
		$("#mcqs_form").submit((e) => {
			e.preventDefault();
			$.ajax({
				data: {
					action: "my_action",
					security: ajaxObject.security,
					serialized: $("#mcqs_form").serialize(),
					exId: $("#ex_id").val(),
				},
				method: "POST",
				url: ajaxObject.ajax_url,
			}).success((_response) => {
				response = JSON.parse(_response);
				console.log(response);
				// check answers
				correct_answers = 0;
				for (i = 1; i <= Object.keys(response).length; i++) {
					userAnswer = $(`div.mcq-${i} input[type=radio]:checked`).val();
					console.log("u:" + userAnswer + "a:" + response[i]);
					if (userAnswer !== undefined && userAnswer == response[i]) {
						correct_answers++;
						answer = { correct: true, message: "Correct answer" };
					} else {
						answer = { correct: false, message: "Incorrect answer" };
					}

					if ($(`div.mcq-${i}`).find("div.answer").length !== 0) {
						if (answer.correct) {
							$(`div.mcq-${i} div.answer`).removeClass("incorrect");
							$(`div.mcq-${i} div.answer`).addClass("correct");
						} else {
							$(`div.mcq-${i} div.answer`).removeClass("correct");
							$(`div.mcq-${i} div.answer`).addClass("incorrect");
						}
						$(`div.mcq-${i} div.answer`).text(answer.message);
					} else {
						$(`div.mcq-${i}`).append(
							`<div class='answer ${
								answer.correct ? "correct" : "incorrect"
							}'>${answer.message}</div>`
						);
					}
				}
				$("#meng_mcqs_result").text(
					`Your result: ${
						Math.round(
							((correct_answers / Object.keys(response).length) * 100 +
								Number.EPSILON) *
								100
						) / 100
					}%`
				);
			});
		});
	});
})(jQuery);
