function checkAccount() {
  console.log("checkaccount");
  const admin = document.querySelector("#admin");
  const organizer = document.querySelector("#organizer");
  const admin_account = document.querySelector(".admin_account");
  if (admin.checked) {
    console.log("admin");
    admin_account.classList.remove("hide");
    setAttributes();
  } else if (organizer.checked) {
    console.log("organizer");
    admin_account.classList.add("hide");
    document.querySelector(".lastname").classList.add("hide");
    document.querySelector("#lastname").classList.remove("validate_error");
    document.querySelector("#lastname").removeAttribute("onkeyup");
    document.querySelector("#lastname").removeAttribute("data-validate");
    document.querySelector("#lastname").removeAttribute("data-min");
    document.querySelector("#lastname").removeAttribute("data-max");
  } else {
    console.log("regular");
    admin_account.classList.add("hide");
    setAttributes();
  }
}

function setAttributes() {
  document.querySelector(".lastname").classList.remove("hide");
  document.querySelector("#lastname").onkeyup = "clear_parent_error(this)";
  document.querySelector("#lastname").setAttribute("data-validate", "str");
  document.querySelector("#lastname").setAttribute("data-min", "2");
  document.querySelector("#lastname").setAttribute("data-max", "30");
}
