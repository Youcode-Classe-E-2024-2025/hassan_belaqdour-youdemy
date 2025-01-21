document
  .querySelector("#signupform")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const username = document.querySelector('input[name="username"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const confirmPassword = document.querySelector(
      'input[name="confirm_password"]'
    ).value;

    const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

    if (!usernameRegex.test(username)) {
      alert(
        "username contient 3-20 characteres avec selement des lettres ,numero et underscores."
      );
      return;
    }

    if (!emailRegex.test(email)) {
      alert("Changer cet email par un autre bien valider ✉️");
      return;
    }

    if (!passwordRegex.test(password)) {
      alert(
        "Le mot de passe doit contenir au moins 8 caractères avec une lettre majuscule et un chiffre"
      );
      return;
    }

    if (password !== confirmPassword) {
      alert("Les mots de passe ne correspondent pas 🖐️");
      return;
    }

    alert("Formulaire soumis avec succes ✅");
    event.target.submit();
  });
