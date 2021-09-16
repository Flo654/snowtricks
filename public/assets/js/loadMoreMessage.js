const elementbutton = document.getElementById("loadMore");

const limitPage = parseInt(elementbutton.dataset.nbofpage);
const page = parseInt(elementbutton.dataset.page);
const trickId = parseInt(elementbutton.dataset.id);
console.log(limitPage, page,elementbutton.getAttribute("href"))

elementbutton.addEventListener("click", (e) => {
  e.preventDefault();
  axios.get(elementbutton.getAttribute("href")).then((response) => {
    let page_next = parseInt(elementbutton.dataset.page) + 1;

    let path = '/' + trickId + "/comments/" + page_next;
    console.log(path)
    if (page_next > limitPage) {
      elementbutton.classList.add("d-none");
      
    }
    document
      .getElementById("comment")
      .insertAdjacentHTML("beforeEnd", response.data);
    elementbutton.setAttribute("href", path);
    elementbutton.dataset.page = page_next;
  });
});