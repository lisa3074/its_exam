//PREVIEW PROFILE IMAGE
function preview() {
  console.log("preview");
  //Select the hidden file input
  const fileinput = document.querySelector("#fileToUpload");
  //if the tag is present
  if (fileinput) {
    //Select the uploaded file
    const uploadedImage = document.querySelector("#fileToUpload").files[0];
  //if there is an uploaded file
    if (uploadedImage) {
      //Change the img.src to the new file (change the preview) 
      const reader = new FileReader();
      reader.onload = function () {
        document.querySelector(".previewImg").src = reader.result;
      };
      reader.readAsDataURL(uploadedImage);
    }
      document.querySelectorAll(".submit_image").forEach(img => {
        img.classList.remove("hide");
      });
      //Find cancel icon
      const cancel = document.querySelector(".submit_image[data-type='cancel']");
      //On click reset file value back to new one
        cancel.addEventListener("click", () => {
            document.querySelector("#fileToUpload").value = "";
            const src = document.querySelector(".previewImg").getAttribute("data-src");
          document.querySelector(".previewImg").src = src;
          //Hide icons
          document.querySelectorAll(".submit_image").forEach(img => {
            img.classList.add("hide");
          });
        });
  }
}