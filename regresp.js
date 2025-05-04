function validateForm() {
  const cin = document.querySelector('[name="cin"]').value.trim();
  const pseudo = document.querySelector('[name="pseudo"]').value.trim();
  const password = document.querySelector('[name="password"]').value.trim();
  const fiscale = document.querySelector('[name="fiscale"]').value.trim();
  const email = document.querySelector('[name="email"]').value.trim();
  const nom = document.querySelector('[name="nom"]').value.trim();
  const prenom = document.querySelector('[name="prenom"]').value.trim();

  const cinValid = /^[0-9]{8}$/.test(cin);
  const pseudoValid = /^[a-zA-Z]+$/.test(pseudo);
  const passwordValid = /^[a-zA-Z0-9]{7,}[$#]$/.test(password);

  const fiscaleValid = /^\$[A-Z]{3}[0-9]{2}$/.test(fiscale);
  const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); 
  const nomValid = /^[a-zA-Z]+$/.test(nom);
  const prenomValid = /^[a-zA-Z]+$/.test(prenom); 

  if (!cinValid) {
    alert("CIN invalide. Il doit contenir exactement 8 chiffres.");
    return false;
  }
  if (!pseudoValid) {
    alert("Pseudo invalide. Il doit contenir uniquement des lettres.");
    return false;
  }
  if (!nomValid) {
    alert("Nom invalide. Il doit contenir uniquement des lettres.");
    return false;
  }
  if (!prenomValid) {
    alert("Prénom invalide. Il doit contenir uniquement des lettres.");
    return false;
  }
  if (!passwordValid) {
    alert("Mot de passe invalide. Il doit contenir au moins 8 caractères, composés de lettres ou de chiffres, et se terminer par $ ou #.");

    return false;
  }
  if (!fiscaleValid) {
    alert("Identifiant fiscal invalide. Format attendu : $ABC12.");
    return false;
  }
  if (!emailValid) {
    alert("E-mail invalide. Veuillez entrer un e-mail valide.");
    return false;
  }

  return true;
}
