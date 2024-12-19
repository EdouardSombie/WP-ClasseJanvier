document.addEventListener("DOMContentLoaded", (event) => {
	ajaxifyLinks();
});

function loadPosts(page, url, args = null) {
	let base = esgiValues.base;
	if (typeof query_args !== "undefined") {
		args = encodeURIComponent(query_args);
	}
	fetch(
		`${esgiValues.ajaxURL}?action=loadPosts&page=${page}&base=${base}&args=${args}`
	).then((response) => {
		response.text().then((text) => {
			document.getElementById("ajax-response").innerHTML = text;
			ajaxifyLinks();
			window.history.pushState({}, "", url);
		});
	});
}

function ajaxifyLinks() {
	document.querySelectorAll(".page-numbers").forEach((elem) => {
		elem.addEventListener("click", (event) => {
			event.preventDefault();
			let current = Number(document.querySelector(".current").innerHTML);
			let page;
			if (event.target.classList.contains("prev")) {
				page = current - 1;
			} else if (event.target.classList.contains("next")) {
				page = current + 1;
			} else {
				page = event.target.innerHTML;
			}

			type = typeof post_type !== "undefined" ? post_type : "post";

			loadPosts(page, event.target.getAttribute("href"), type);
		});
	});
}
