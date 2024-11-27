document.addEventListener("DOMContentLoaded", (event) => {
	ajaxifyLinks();
});

console.log(esgiValues.ajaxURL);
console.log(esgiValues.base);
function loadPosts(page, url) {
	let base = esgiValues.base;
	fetch(
		`${esgiValues.ajaxURL}?action=loadPosts&page=${page}&base=${base}`
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

			loadPosts(page, event.target.getAttribute("href"));
		});
	});
}
