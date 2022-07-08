<?php

require_once 'lib.php';
require_once 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $data = json_decode(file_get_contents("php://input"), true);

  if (isset($data['name'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    {//Get form
      $users = getUsers();
      $form = false;
      foreach ($users as $u) {
        if($u['login'] == $data['login']){
          $form = $u['form'];
          break;
        }
      }
      if(!$form){
        echo json_encode(0); exit;
      } 
    }
    
    {//Send Comment
      //The url you wish to send the POST request to
      $url = $form;

      //The data you want to send via POST
      $fields = [
          'send'        => 1,
          'from'        => $data['name'],
          'for'         => $data['username'],
          'comment'     => $data['comment'],
          'songartist'  => $data['artist'],
          'songname'    => $data['songtitle'],
      ];

      //url-ify the data for the POST
      $fields_string = http_build_query($fields);

      //open connection
      $ch = curl_init();

      //set the url, number of POST vars, POST data
      curl_setopt($ch,CURLOPT_URL, $url);
      curl_setopt($ch,CURLOPT_POST, true);
      curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

      //So that curl_exec returns the contents of the cURL; rather than echoing it
      curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

      //execute post
      $result = curl_exec($ch);
      echo $result;
      // echo json_encode($result);
      exit;
    }

  }

  if (isset($data['action']) && $data['action'] == 'currentTrack') {

    {//Make link
      $myradioLink = str_replace('https', '', base64_decode($data['player']));
      $myradioLink = str_replace('http', '', $myradioLink);
      $myradioLink = str_replace('myradio24.com', '', $myradioLink);
      $myradioLink = str_replace('myradio24.org', '', $myradioLink);
      $myradioLink = str_replace('/', '', $myradioLink);
      $myradioLink = str_replace(':', '', $myradioLink);
      $myradioLink = "https://myradio24.org/users/$myradioLink/status.json";
    }

    $myradioData = json_decode(@file_get_contents($myradioLink));

    $rData['artist'] = $myradioData->artist;
    $rData['songtitle'] = $myradioData->songtitle;
    echo json_encode($rData);
    exit;
  }

}


?>

<style>
  .radio-form{
    font-size:12pt;  
    font-weight: 600;
    font-family: helvetica;
  }
  .border-rounded{
    border: 1px solid gray;
    border-radius: 5px;    
  }
  .label{
    color:<?php echo $user['color']?>;
  }

  #inputs-container{
    margin: 0px 40px;
  }

  @media screen and (max-width: 500px) {
    #inputs-container{
      margin: 0px 10px;
    }
  }

</style>

<?php if(isset($user['form'])) : ?>
  <div class="radio-form" style="
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 95%;
    max-width: 500px;
    margin: 30px auto;
    border: 1px solid gray;
    border-radius: 5px;
    background-color: #ffffff;
    padding-top: 20px;
  ">

    <div>
      <div style="display: flex; justify-content: center;">
        <span class="label">Сейчас играет:</span>
      </div>
      <div style="color: gray; margin:10px">
        <span id="currentArtist"></span> - 
        <span id="currentSongtitle"></span>
      </div>
    </div>


    <form id="feedback-form" action=""  style="
      width: 100%;
    ">

      <div id="inputs-container">

        <!-- Name -->
        <input id="name-input" type="hidden" name="name" value="1" placeholder="Имя" required style="
          display: block;
          width: 300px;
          background-color: #fff;
          border-radius: 10px;
          border: 1px solid gray;
          height: 40px;
          margin: 10px 0;
          padding: 5px;
        ">

        <!-- Login -->
        <input id="login-input" type="hidden" name="login" value="<?php echo $user['login']?>">

        <!-- Username -->
        <input id="username-input" type="hidden" name="username" value="<?php echo isset($user['username']) ? $user['username'] : 'nousername'?>">

        <!-- Song\Artist -->
        <div style="display:flex; margin-top: 20px; justify-content: space-between;">
          <span style="max-width: 45%;">
            <label for="artist-input" class="label">Исполнитель</label>
            <input id="artist-input" class="border-rounded" type="text" name="artist" required style="margin-top: 5px; height: 35px; max-width: 100%;">
          </span>
          <span style="max-width: 45%; justify-content: end;display: flex;flex-direction: column;">
            <label for="songtitle-input" class="label">Название трека</label>
            <input id="songtitle-input" class="border-rounded" type="text" name="songtitle" required style="margin-top: 5px; height: 35px; max-width: 100%;">
          </span>

        </div>

        <!-- Comment -->
        <div style="width: 100%; margin-top: 20px;">
          <label for="comment-input" class="label">Комментарий</label>
          <textarea id="comment-input" class="border-rounded" name="" id="" cols="30" rows="10" placeholder="" required style="
            display: block; 
            width: 100%;
            background-color: #fff;
            height: 100px;
            padding: 5px;
          "></textarea>
        </div>

      </div>
      
      <div style="
        width: 100%;
        background: #E6E6E6;
        margin-top: 20px;
        border-top: 1px solid gray;
        height: 75px;
        border-radius: 0 0 5px 5px;
        display: flex;
        justify-content: center;
        align-items: center;
      ">

        <!-- Button -->
        <button id="submit-button" type="submit" style="
          margin: 10px;
          padding: 0 10px;
          color: #fff;
          background-color: <?php echo $user['color']?>;
          border-color: <?php echo $user['color']?>;
          border-radius: 5px;    
          height: 35px;
          width: 150px;
          font-size: 12pt;
        ">Отправить</button>

        
        <!-- Status -->
        <div id="submit-status" style="color:<?php echo $user['color']?>"></div>

      </div>




    </form>


    <script>

      getCurrentTrack();
      setInterval(() => {
        getCurrentTrack();
      }, <?php echo $songDelay?>);


      $("#feedback-form").on('submit', (e) => {
        e.preventDefault();

        $("#submit-status").hide();
        $("#submit-button").hide();

        data = {}
        data.artist = $('#artist-input').val()
        data.songtitle = $('#songtitle-input').val()
        data.name = $('#name-input').val()
        data.username = $('#username-input').val()
        data.login = $('#login-input').val()
        data.comment = $('#comment-input').val()

        console.log(data);

        $('#artist-input').val("")
        $('#songtitle-input').val("")
        $('#comment-input').val("")
        // $('#name-input').val("")
        
        axios.post('form.php', data)
          .then(function (response) {
            // handle success
            console.log(response.data);

            res = response.data;
            $("#submit-status").show();
            if(res.ok != undefined && res.ok == 1){
              $("#submit-status").text('Cообщение отправлено!');
            }else if(res.err != undefined && res.err != ""){
              $("#submit-status").text('Ошибка: Попробуйте позднее.');
            }else{
              $("#submit-status").text('Что-то пошло не так');
            }

            setTimeout(() => {
              $('#submit-status').hide();
              $("#submit-button").show();
            }, <?php echo $sendDelay?>);
            
          });


      });

      function getCurrentTrack(){
        axios.post('form.php', {'action':'currentTrack','player': "<?php echo $user['player']?>"})
          .then(function (response) {
            // handle success
            console.log(response.data);

            $('#currentArtist').text(response.data.artist);
            $('#currentSongtitle').text(response.data.songtitle);
          });

      }

    </script>

  </div>
<?php endif; ?>