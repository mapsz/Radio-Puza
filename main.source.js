<script>
    
  $( document ).ready(function() {
      var music = document.getElementById("music");
      music.src = atob('<?php echo $user['player']?>');
      setTimeout(() => {
          music.removeAttribute('src');
      }, 0);
  });

  //Check Session
  setInterval(() => {
    getSession()
  }, 10000);

  async function getSession(){      
    axios.get('checkSession.php?login=' + document.getElementById('login').value)
      .then(function (response) {
        // handle success
        if(document.getElementById('session').value != response.data){
          console.log(document.getElementById('login').value);
          console.log(response.data);
          console.log('-----');
          location.href = 'logout.php'
        }
      })
  }

</script>