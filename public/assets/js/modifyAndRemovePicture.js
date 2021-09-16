const input = document.querySelectorAll(".custom-file-input");
const button = document.querySelectorAll(".modify");
const removeButton = document.querySelectorAll(".remove");
const remove_video_Link = document.querySelectorAll(".remove_video");

input.forEach((element) => {
  element.classList.add("d-none");
});
// modification d'images
button.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const thisInput = e.currentTarget.parentNode.parentNode.querySelector("input");
    const image = e.currentTarget.parentNode.parentNode.querySelector(".previsu");
    thisInput.click();
    thisInput.addEventListener("change", (e) => {
      image.src = URL.createObjectURL(e.currentTarget.files[0]);
    });
  });
});

//suppression d'images
removeButton.forEach((btn) => {
  btn.addEventListener("click", async function (e) {
    e.preventDefault();
    if (confirm("Voulez-vous supprimer cette image ?")) {
      this.parentNode.parentNode.remove();
    }
  });
});

remove_video_Link.forEach((btn) => {
  btn.addEventListener("click", async function (e) {
    e.preventDefault();
    if (confirm("Voulez-vous supprimer cette video ?")) {
      this.parentNode.remove();
    }
  });
});
