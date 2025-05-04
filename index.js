document.getElementById('connexionBtn').addEventListener('click', function () {
    document.getElementById('mainContent').style.display = 'none';
    document.getElementById('loginPage').style.display = 'block';
  });
  
  document.getElementById('returnBtn').addEventListener('click', function () {
    document.getElementById('loginPage').style.display = 'none';
    document.getElementById('mainContent').style.display = 'block';
  });
  
  window.addEventListener('load', () => {
    const params = new URLSearchParams(window.location.search);
    const loginStatus = params.get('login');
  
    if (loginStatus) {
      let message = '';
      switch (loginStatus) {
        case 'incorrect_donateur':
          message = '❌ Mot de passe incorrect (Donateur).';
          break;
        case 'incorrect_reponsable':
          message = '❌ Mot de passe incorrect (Responsable).';
          break;
        case 'pseudo_introuvable':
          message = '❌ Pseudo introuvable dans les deux comptes.';
          break;
        case 'empty':
          message = '❌ Veuillez remplir tous les champs.';
          break;
      }
  
      if (message) {
        alert(message);
        document.getElementById('mainContent').style.display = 'none';
        document.getElementById('loginPage').style.display = 'block';
      }
    }
  });
  