function checkAccount() {
  const admin = document.querySelector("#admin");
  const organizer = document.querySelector("#organizer");
  const admin_account = document.querySelector(".admin_account");

  /* Which accout type was checked -> hide / show input fields */
  if (admin.checked) {
    admin_account.classList.remove("hide");
    setAttributes();
  } else if (organizer.checked) {
    admin_account.classList.add("hide");
    document.querySelector(".lastname").classList.add("hide");
      /* Remove attributes on last name input field in signup, if acoount checked is organizer */
    document.querySelector("#lastname").classList.remove("validate_error");
    document.querySelector("#lastname").removeAttribute("onkeyup");
    document.querySelector("#lastname").removeAttribute("data-validate");
    document.querySelector("#lastname").removeAttribute("data-min");
    document.querySelector("#lastname").removeAttribute("data-max");
  } else {
    admin_account.classList.add("hide");
    setAttributes();
  }
}

function setAttributes() {
    /* Reset attributes on last name input field in signup, if acoount checked is not organizer */
  document.querySelector(".lastname").classList.remove("hide");
  document.querySelector("#lastname").onkeyup = "clear_parent_error(this)";
  document.querySelector("#lastname").setAttribute("data-validate", "str");
  document.querySelector("#lastname").setAttribute("data-min", "2");
  document.querySelector("#lastname").setAttribute("data-max", "30");
}
