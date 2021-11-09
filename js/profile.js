
function openPopup(wrapper, popup) {
  document.querySelector("." + wrapper).classList.remove("hide");
  closePopup(wrapper, popup);
}
//POPUPS
function hidePopup(wrapper, popup) {
  console.log("hide");
  document.querySelector("." + wrapper).classList.toggle("hide");
  closePopup(wrapper, popup);
}

function closePopup(wrapper, popup) {
  console.log("close " + wrapper, popup);
  document.querySelector("." + wrapper).addEventListener("click", () => {
    document.querySelector("." + wrapper).classList.add("hide");
  });
  if (popup == "deactivate_popup") {
    document.querySelector("." + popup).addEventListener("click", e => e.stopPropagation());
  }
}


//PREVIEW PROFILE IMAGE
function preview() {
  console.log("preview");
  const profile = document.querySelector("#fileToUpload");

  if (profile) {
    const uploadedImage = document.querySelector("#fileToUpload").files[0];
    if (uploadedImage) {
      const reader = new FileReader();
      reader.onload = function () {
        document.querySelector(".previewImg").src = reader.result;
      };
      reader.readAsDataURL(uploadedImage);
    }
    if (document.querySelector("#fileToUpload")) {
      document.querySelectorAll(".submit_image").forEach(img => {
        img.classList.remove("hide");
      });
        const label = document.querySelector('.submit_image');
        label.addEventListener("click", () => {
          if (label.dataset.type == "cancel") {
            document.querySelector("#fileToUpload").value = "";
            const src = document.querySelector(".previewImg").getAttribute("data-src");
            document.querySelector(".previewImg").src = src;
          }
          document.querySelectorAll(".submit_image").forEach(img => {
            img.classList.add("hide");
          });
      });
    }
  }
}

