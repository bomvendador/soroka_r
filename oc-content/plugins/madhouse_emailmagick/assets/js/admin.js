(function($) {
	// Array of CodeMirror instances.
	var editors = {};

	function initCodeMirror(e)
	{
		return CodeMirror.fromTextArea(
			e.get(0),
			{
				indentUnit: 4,
				lineNumbers: true,
				lineWrapping: true,
				matchBrackets: true,
				mode: "text/html"
			}
		);
	}

	function handleCodeMirror(id, editor)
	{
		// Create the editor if it does not exists.
		if(editors[id] === undefined) {
			editors[id] = initCodeMirror(editor);
		}

		// Refresh.
		if(editors[id]) {
			editors[id].refresh();
		}
	}

	$(document).ready(function() {

		// Init the template Codemirror.
		initCodeMirror($("[name='email_template']"));

		// Enable the first tab of each .nav-tabs
		$(".nav.nav-tabs li:first-child a").tab("show");


		// Inits every locales' codemirror.
		$("#footer-tabs").find(".codemirror-email").each(function(i, e) {
			editors[$(e).attr("id")] = initCodeMirror($(e).find(".codemirror-textarea"));
		});

		$('.panel-collapse').on('shown.bs.collapse', function (event) {
			var $tab = $(event.target).find(".tab-pane.active");
			if($tab) {
				handleCodeMirror($tab.attr("id"), $tab.find(".codemirror-textarea"));
			}
		});

		// Init (or refresh) Codemirror's editors when inside tabs.
		$('.nav.nav-tabs').on('shown.bs.tab', function (event) {
			var target = $(event.target).attr("href"),
				$panel = $(target);
			handleCodeMirror($panel.attr("id"), $panel.find(".codemirror-textarea"));
		});

		// Handle modal validation and deletion of a remaining email (that does not exists in oc_t_pages).
		$(".action-delete").on("click", function(e) {
			var $this = $(this),
				$modal = $this.parents(".modal");
			$modal.data("confirm", true);
		});

		$(".modal").on("hidden.bs.modal", function(e) {
			var $modal = $(e.target),
				$panel = $modal.parents(".panel");

			if($modal.data("confirm") == true) {
				console.log("YES");
				$panel.remove();
			}
		});

		$("form").submit(function() {
			var data = {
				"emails": $.map($(".email-wrapper"), function(e, i) {
					return {
						"s_internal_name": $(e).find(".email-name").text(),
						"locales": $.map($(e).find(".email-description"), function(d, i){
							var $d = $(d);
							if(editors[$d.attr("id")] !== undefined) {
								editors[$d.attr("id")].save();
							}
							return {
								"fk_c_locale_code": $d.data("locale"),
								"s_title": $d.find(".email-title").val(),
								"s_excerpt": $d.find(".email-excerpt").val(),
								"s_text": $d.find(".email-text").val()
							}
						})
					}
				}),
				"footer": {
					"locales": $.map($(".footer-description"), function(e, i) {
						return {
							"fk_c_locale_code": $(e).data("locale"),
							"s_text": $(e).find(".footer-text").val()
						}
					})
				}
			};

			$(this).append('<textarea style="display:none;" name="email_datas">' + JSON.stringify(data) + '</textarea>');
			return true;
		});

	});
})(jQuery);
