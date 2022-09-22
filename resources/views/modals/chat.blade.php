<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <section style="background-color: #eee;">
                <div class="container py-5">
                    <div class="row d-flex justify-content-center">
                        <div class="">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center p-3"
                                     style="border-top: 4px solid #ffa900;">
                                    <h5 class="mb-0">Chat messages</h5>
                                    <div class="d-flex flex-row align-items-center">
                                        <span class="badge bg-warning me-3">20</span>
                                        <i class="fas fa-minus me-3 text-muted fa-xs"></i>
                                        <i class="fas fa-comments me-3 text-muted fa-xs"></i>
                                        <i class="fas fa-times text-muted fa-xs"></i>
                                    </div>
                                </div>
                                <div class="card-body overflow-auto"
                                     id="cardBody" data-mdb-perfect-scrollbar="true"
                                     style="position: relative; height: 400px">

                                    <div class="d-flex justify-content-between">
                                        <p class="small mb-1">Timona Siera</p>
                                        <p class="small mb-1 text-muted">23 Jan 2:00 pm</p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-start">
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                             alt="avatar 1" style="width: 45px; height: 100%;">
                                        <div>
                                            <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">For what
                                                reason
                                                would it
                                                be advisable for me to think about business content?</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="small mb-1 text-muted">23 Jan 2:05 pm</p>
                                        <p class="small mb-1">Johny Bullock</p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-end mb-4 pt-1">
                                        <div>
                                            <p class="small p-2 me-3 mb-3 text-white rounded-3 bg-warning">Thank you for your
                                                believe in
                                                our
                                                supports</p>
                                        </div>
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                             alt="avatar 1" style="width: 45px; height: 100%;">
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <p class="small mb-1">Timona Siera</p>
                                        <p class="small mb-1 text-muted">23 Jan 5:37 pm</p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-start">
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                             alt="avatar 1" style="width: 45px; height: 100%;">
                                        <div>
                                            <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">Lorem
                                                ipsum dolor
                                                sit amet
                                                consectetur adipisicing elit similique quae consequatur</p>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <p class="small mb-1 text-muted">23 Jan 6:10 pm</p>
                                        <p class="small mb-1">Johny Bullock</p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-end mb-4 pt-1">
                                        <div>
                                            <p class="small p-2 me-3 mb-3 text-white rounded-3 bg-warning">Dolorum quasi
                                                voluptates quas
                                                amet in
                                                repellendus perspiciatis fugiat</p>
                                        </div>
                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                             alt="avatar 1" style="width: 45px; height: 100%;">
                                    </div>

                                </div>
                                <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                                    <div class="input-group mb-0">
                                        <input type="text" class="form-control" id="myInput" placeholder="Type message"
                                               aria-label="Recipient's username" aria-describedby="button-addon22"/>
                                        <button class="btn btn-warning send-message-button"
                                                type="button"
                                                id="button-addon22"
                                                style="padding-top: .55rem;">
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<script>

    $(document).ready(() => {
        console.log('is ready');
        $('.open-chat-btn').on('click', (e) => {
            $("#cardBody").animate({ scrollTop: document.body.scrollHeight, behavior: 'smooth' }, "slow");
            const tableData = document.getElementById('cardBody')

            const userId = $(e)[0].target.dataset.user_id;
            axios.post('/message/open', { id : userId}).then((res) => {
              const chatMessages =  res.data.chatMessages;
                const sender = res.data.user;
                const friend = res.data.friend;
              buildData(chatMessages, userId ,sender , friend)
            });
            $('.send-message-button').val(userId);
        });

        $(document).on('show.bs.modal','#myModal', function () {

        })

        $('#button-addon22').on("click" , (() => {
            const userId = $('.send-message-button').val()
            const message = $('#myInput').val()
            let data = {
                "id": userId,
                "description": message,
            };

            axios.post('/send/message', data);
            $('#myInput').val('');


            Pusher.logToConsole = true;
            let pusher = new Pusher('4acf7b4d2ff2249e1564', {
                cluster: 'ap2',
                // encrypted: true
            });
            const tableData = document.getElementById('cardBody')

           let  channel = pusher.subscribe('my-channel');
            let count  = 0;
            let row = '';
            console.log(11111);
            channel.bind('App\\Events\\SendMessageNotification', function (data) {
                console.log(22222);
                // count+=1

                    if (userId === data.message.getter_id ) {
                        row =
                            `
                       <div class="d-flex justify-content-between">
                                        <p class="small mb-1">${data.message['userName']}</p>
                                        <p class="small mb-1 text-muted">23 Jan 2:00 pm</p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-start">
                                       <img src="{{asset("storage/images/icons8-user-80.png")}}"
                                             alt="avatar 1" style="width: 45px; height: 100%;">
                                        <div>
                                            <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">${data.message['message']}</p>
                                        </div>
                      `

                        $('#cardBody').append(row)
                        // channel.unbind('App\\Events\\SendMessageNotification')
                        row = ''
                    } else {
                        row =
                            `
                       <div class="d-flex justify-content-between">
                                        <p class="small mb-1 text-muted">23 Jan 2:00 pm</p>
                                        <p class="small mb-1 text-muted">${data.message.friend['name']}</p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-end mb-4 pt-1">
                                        <div>
                                            <p class="small p-2 me-3 mb-3 text-white rounded-3 bg-warning">${data.message['message']}</p>
                                        </div>
                                        <img src="{{asset("storage/images/icons8-user-80.png")}}"
                                             alt="avatar 1" style="width: 45px; height: 100%;">
                                    </div>
                      `

                        $('#cardBody').append(row)
                        channel.unbind('App\\Events\\SendMessageNotification')

                        row = ''
                    }

                // if (count > 1 ){
                //     channel.unbind('App\\Events\\SendMessageNotification')
                // }

                // tableData.innerHTML += row
                // $('#cardBody').append(row)
                // row = ''




            });
         }));

        function getDate(date) {
            let d = new Date(date);
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];

            return  d.getDate()  + " " + (monthNames[d.getMonth()]) + " " +
                d.getHours() + ":" + d.getMinutes();
        }

        function buildData(chatMessages , userId , user ,friend)
        {
            const tableData = document.getElementById('cardBody')
            chatMessages.sort(function(a,b){
                return new Date(a.created_at) - new Date(b.created_at);
            });

            for (let i = 0 ; i< chatMessages.length ; i++){
              if (chatMessages[i].getter_id != userId){
                  const row =
                      `
                       <div class="d-flex justify-content-between">
                                        <p class="small mb-1 text-muted">${getDate(chatMessages[i].created_at)}</p>
                                        <p class="small mb-1">${friend.name}</p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-end mb-4 pt-1">
                                        <div>
                                            <p class="small p-2 me-3 mb-3 text-white rounded-3 bg-warning">${chatMessages[i].description}</p>
                                        </div>
                                        <img src="{{asset("storage/images/icons8-user-80.png")}}"
                                             alt="avatar 1" style="width: 45px; height: 100%;">
                                    </div>

                      `
                  tableData.innerHTML += row
              }  else {
                  let row =
                      `
                       <div class="d-flex justify-content-between">
                                        <p class="small mb-1">${user.name}</p>
                                        <p class="small mb-1 text-muted">23 Jan 2:00 pm</p>
                                    </div>
                                    <div class="d-flex flex-row justify-content-start">
                                    <img src="{{asset("storage/images/icons8-user-80.png")}}"
                                             alt="avatar 1" style="width: 45px; height: 100%;">
                                        <div>
                                            <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">${chatMessages[i].description}</p>
                                        </div>
                      `
                  tableData.innerHTML += row
              }
            }
        }
    });
</script>


