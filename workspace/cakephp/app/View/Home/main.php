<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo h($page); ?></h1>

    <div>
        Hello, <?php echo $firstName ?>!
    </div>

</div>



<!-- chat-area -->
<section class="message-area">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="chat-area">
                    <!-- chatlist -->
                    <div class="chatlist">
                        <div class="modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="chat-header">
                                    <div class="msg-search">
                                        <input type="text" class="form-control w-100" id="userSearchInput"
                                            placeholder="Search" aria-label="search">
                                    </div>
                                </div>

                                <div class="modal-body mt-3">

                                    <!-- chat-list -->
                                    <div class="chat-lists" id="userList">
                                        <!-- Users List here -->
                                    </div>

                                    <button id="showMoreChatList" class="btn btn-outline-secondary w-100 btn-sm mb-3">Show More</button>
                                    <button id="showLessChatList" class="btn btn-outline-secondary w-100 btn-sm" style="display: none;">Show Less</button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- chatlist -->



                    <!-- chatbox -->
                    <div class="chatbox">
                        <div class="modal-dialog-scrollable">
                            <div class="modal-content">

                                <!-- Chat Header Placeholder -->
                                <div class="msg-head" id="chatHeader">
                                    <!-- User information dynamically updated here -->
                                </div>

                                <!-- Chat Messages Placeholder -->
                                <div class="modal-body">
                                    <div class="msg-body" id="chatMessages">
                                        <!-- Messages will be loaded here -->
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button id="showMoreMessageButton" class="btn btn-outline-secondary btn-md d-none w-md-auto show-more-btn">
                                            Show More Message
                                        </button>
                                    </div>
                                </div>


                                <div class="send-box">
                                    <form id="replyForm">
                                        <input type="hidden" name="recipient_id" id="recipientId">
                                        <input type="text" name="message" id="messageInput" class="form-control" placeholder="Write message…">
                                        <button type="button" id="sendMessage"><i class="bi bi-send-fill"></i> Send</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- chatbox -->


            </div>
        </div>
    </div>
    </div>


    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="fullMessageContent">Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- chat-area -->

<script>
    $(document).ready(function() {
        let loading = false;
        let msgId;
        let usersResponse;

        let currentChatListPage = 1;
        const usersPerPage = 10;

        let currentPage = 1;
        const messagesPerPage = 10;
        let userId;

        function loadChatList(searchUser = '') {

            const limit = currentChatListPage * usersPerPage;

            $.ajax({
                url: '/cakephp/home/chatlist_api',
                method: 'GET',
                data: {
                    search: searchUser,
                    limit: limit
                },
                dataType: 'json',
                success: function(response) {
                    usersResponse = response;

                    $(document).on('click', '.chat-icon', function() {
                        $(".chatbox").removeClass('showbox');
                    });

                    if (response.users && response.users.length > 0) {
                        let userListHtml = '';

                        $.each(response.users, function(index, user) {

                            const latestMessage = user.LatestMessage.message ?? 'No message found.';
                            const wordCount = latestMessage.split(' ').length;

                            // Prepare the message display
                            let messageDisplay;
                            let viewMoreHtml = '';

                            if (wordCount > 4) {
                                // Show the first 4 words and prepare the eye icon
                                const firstFourWords = latestMessage.split(' ').slice(0, 4).join(' ') + '...';
                                messageDisplay = firstFourWords;
                                viewMoreHtml = `<span class="view-more" style="color: blue; cursor: pointer;"><i class="bi bi-eye fs-5"></i></span>`;
                            } else {
                                // Show the full message if 4 words or less
                                messageDisplay = latestMessage;
                            }

                            userListHtml += `
                            <div class="chat-list">
                                <a href="#" class="d-flex align-items-center user-link" data-id="${user.User.id}">
                                    <div class="flex-shrink-0">
                                        <img class="img-fluid rounded-circle" src="${user.User.profile_url == null ? 'https://i.ibb.co/pW9DJrH/blank-twitter-icon.webp' : user.User.profile_url }" alt="${user.User.name}'s photo" width="50" height="50">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3>${user.User.name}</h3> <!-- Updated 'username' to 'name' -->
                                        <p class="latest-message" style="font-size:12px;">${messageDisplay} ${viewMoreHtml}</p>
                                        <span class="d-none" id="userMail">${user.User.email}</span>
                                    </div>
                                </a>
                            </div>`;
                        });


                        $('#userList').html(userListHtml);

                        $('#showMoreChatList').toggle(limit < response.totalUsers);
                        // $('#showLessChatList').toggle(currentChatListPage > 1);

                        if (currentChatListPage === 1) {
                            //trigger first chat
                            var firstChatItem = $('.user-link').first();
                            if (firstChatItem.length > 0) {
                                firstChatItem.trigger('click');
                            }
                        }
                    } else {
                        $('#userList').html('<p>No users available.</p>');
                        $('#showMoreChatList').hide();
                        $('#showLessChatList').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching chat list:", error);
                    $('#userList').html('<p>Error loading users. Please try again later.</p>');
                }
            });
        }

        $(document).on('click', '.view-more', function() {
            const parent = $(this).closest('.chat-list');
            const userId = parent.find('.user-link').data('id');

            if (usersResponse) {
                // Find the full message
                const fullMessage = usersResponse.users.find(u => u.User.id === userId).LatestMessage.message;
                $('#fullMessageContent').text(fullMessage);
                $('#messageModal').modal('show');
            } else {
                console.error('User response not available');
            }
        });

        $(document).on('input', '#userSearchInput', function() {
            const searchTerm = $(this).val().trim();
            loadChatList(searchTerm);
        });

        $(document).on("click", ".user-link", function(e) {
            e.preventDefault();
            userId = $(this).data("id");

            scrollToBottom()

            $(".chatbox").addClass('showbox');

            $('#recipientId').val(userId);
            loadMessages(userId, currentPage);

            // Optional: Update chat header with the clicked user's profile
            var clickedUserName = $(this).find("h3").text();
            var clickedUserEmail = $(this).find("span").text();
            var clickedUserPhoto = $(this).find("img").attr("src");
            $('#chatHeader').html(`
            <div class="row">
            <div class="col-8">
                <div class="d-flex align-items-center">
                    <span class="chat-icon"><img class="img-fluid" src="https://mehedihtml.com/chatbox/assets/img/arroleftt.svg" alt="back"></span>
                    <div class="flex-shrink-0">
                        <img class="img-fluid rounded-circle ms-2" src="${clickedUserPhoto == null ? 'https://i.ibb.co/pW9DJrH/blank-twitter-icon.webp' : clickedUserPhoto }" alt="user img" width="33">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3>${clickedUserName}</h3>
                        <p>${clickedUserEmail}</p>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <ul class="moreoption">
                    <li class="navbar nav-item text-center d-flex">
                        <input class="col-3 form-control d-none ms-3" placeholder="Search Message...">
                        <i class="bi bi-search fs-5 me-1 mt-2" id="showSearchMessage" data-userId="${userId}"></i>
                    </li>
                </ul>
            </div>
                                            
            </div>
        `);
        });

        $(document).on('click', '#showSearchMessage', function() {
            const searchInput = $(this).closest('.moreoption').find('input');
            searchInput.toggleClass('d-none'); // Toggle the d-none class to show/hide the input

            const userId = $(this).attr('data-userId');

            if (searchInput.is(':visible')) {
                $(this).removeClass('bi-search').addClass('bi-x');
                searchInput.val('');
            } else {
                $(this).removeClass('bi-x').addClass('bi-search');
                loadMessages(userId, currentPage);
            }

        });

        $(document).on('input', 'input[placeholder="Search Message..."]', function() {
            const searchTerm = $(this).val().trim();
            const userId = $('#recipientId').val();
            loadMessages(userId, currentPage, searchTerm);
        });

        function loadMessages(userId, page, searchTerm = '') {
            if (loading) return; // Prevent loading if already loading
            loading = true; // Set loading flag 

            $.ajax({
                url: '/cakephp/home/load_messages',
                method: 'POST',
                data: {
                    id: userId,
                    page: page,
                    searchTerm: searchTerm
                },
                dataType: 'json', // Expecting JSON response
                success: function(response) {

                    let messagesHtml = '<ul class="p-0">';

                    if (response.result && response.result.length > 0) {
                        $.each(response.result, function(index, message) {
                            if (message.divider) {
                                messagesHtml += `<li><div class="divider"><h6>${message.divider}</h6></div></li>`;
                            } else {
                                const iconPositionClass = message.class === 'sender' ? 'icon-right' : 'icon-left';

                                const isLongMessage = message.content.length > 50;
                                const truncatedMessage = isLongMessage ? message.content.slice(0, 50) + '...' : message.content;

                                messagesHtml += `
                                <li class="${message.class}">
                                    ${message.class === 'reply' ? `
                                        <div class="message-icon icon-left">
                                            <i class="bi bi-pencil-square fs-5 edit-message ${message.showEdit ? '' : 'd-none'}" data-id="${message.msgId}" data-message="${message.content}"></i>
                                            <i class="bi bi-trash fs-5 delete-message ${message.showDelete ? '' : 'd-none'}" data-id="${message.msgId}"></i>
                                        </div>` : ''}
                                    
                                    <div class="message-wrapper">
                                        <p class="message-content">${truncatedMessage}
                                            ${isLongMessage ? `<a href="javascript:void(0);" class="${message.class === 'sender' ? `text-dark` : `text-light`}  toggle-message" data-full-message="${message.content}"><i class="bi bi-eye"></i></a>` : ''}
                                        </p>
                                    </div>
                                    
                                    ${message.class === 'sender' ? `
                                        <div class="message-icon icon-right">
                                            <i class="bi bi-pencil-square fs-5 edit-message ${message.showEdit ? '' : 'd-none'}" data-id="${message.msgId}" data-message="${message.content}"></i>
                                            <i class="bi bi-trash fs-5 delete-message ${message.showDelete ? '' : 'd-none'}" data-id="${message.msgId}"></i>
                                        </div>` : ''}
                                    
                                    <span class="time mt-1 ms-1">${message.time}</span>
                                </li>`;
                            }
                        });

                        messagesHtml += '</ul>';
                        $('#chatMessages').html(messagesHtml); // Load the messages

                        if (response.totalMessages > page * messagesPerPage) {
                            $('#showMoreMessageButton').removeClass('d-none');
                        } else {
                            $('#showMoreMessageButton').addClass('d-none');
                        }

                        currentPage++;

                        bindMessageEvents();

                    } else {
                        $('#chatMessages').html('<ul><li>No messages found.</li></ul>');
                        $('#showMoreMessageButton').addClass('d-none');
                    }


                    loading = false; // Reset loading flag 
                    //  scrollToBottom();
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching messages:", error);
                    $('#chatMessages').html('<li>Error loading messages. Please try again later.</li>');
                    loading = false; // Reset loading flag 
                }
            });
        }

        function bindMessageEvents() {
            // Edit message
            $(document).on('click', '.edit-message', function() {
                msgId = $(this).attr('data-id');
                const msgContent = $(this).attr('data-message');
                $('#messageInput').val(msgContent);
                $('html, body').animate({
                    scrollTop: $('#messageInput').offset().top
                }, 500);
            });

            // Delete message
            $(document).on('click', '.delete-message', function() {
                msgId = $(this).attr('data-id');
                removeMessage(msgId);
            });

            // Toggle message visibility
            $(document).on('click', '.toggle-message', function() {

                const messageWrapper = $(this).closest('.message-wrapper');
                const messageContent = messageWrapper.find('.message-content');
                const originalMessage = $(this).data('full-message');

                // Determine button style based on message class
                const messageClass = messageWrapper.closest('li').hasClass('sender') ? 'text-dark' : 'text-light';

                // Check if content is currently expanded
                if (messageContent.hasClass('expanded')) {
                    // Switch to truncated content
                    messageContent.removeClass('expanded');
                    messageContent.html(originalMessage.length > 50 ? originalMessage.slice(0, 50) + '...' : originalMessage);
                    // Re-add the "Show More" button
                    messageContent.append(` <a href="javascript:void(0);" class="${messageClass} toggle-message" data-full-message="${originalMessage}"> <i class="bi bi-eye"></i></a>`);
                } else {
                    // Switch to full content
                    messageContent.addClass('expanded');
                    messageContent.html(originalMessage + ` <a href="javascript:void(0);" class="${messageClass} toggle-message" data-full-message="${originalMessage}"><i class="bi bi-eye-slash"></i></a>`);
                }
            });
        }

        $('#sendMessage').click(function() {
            var messageText = $('#messageInput').val();
            var recipientId = $('#recipientId').val();

            if (messageText.trim() !== "") {
                $.ajax({
                    url: '/cakephp/home/send_message',
                    method: 'POST',
                    data: {
                        messageId: msgId,
                        message: messageText,
                        recipient_id: recipientId
                    },
                    success: function(response) {
                        toast('success','Message Updated!')
                        loadMessages(recipientId, currentPage);
                        $('#messageInput').val('');
                        scrollToBottom()
                    },
                    error: function(response) {
                        console.log(response)
                    }
                });
            } else {
                alert('Please type a message')
            }
        });

        function removeMessage(msgId) {
            var recipientId = $('#recipientId').val();

            const messageElement = $(`[data-id="${msgId}"]`).closest('li');

            messageElement.fadeOut(300, function() {
                $.ajax({
                    url: '/cakephp/home/remove_message',
                    method: 'POST',
                    data: {
                        messageId: msgId,
                    },
                    success: function(response) {
                        toast('success','Message Deleted!')
                        loadMessages(recipientId, currentPage);

                    },
                    error: function(response) {
                        console.log(response);
                        messageElement.fadeIn();
                    }
                });
            });
        }

        function scrollToBottom() {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        const modalDialog = document.getElementsByClassName('modal-dialog-scrollable')[0]; // Access the first element
        if (modalDialog) {
            modalDialog.addEventListener('shown.bs.modal', function() {
                scrollToBottom();
            });
        }

        $('#showMoreMessageButton').on('click', function(e) {
            e.preventDefault();
            loadMessages(userId, currentPage);
        });

        loadChatList();

        $('#showMoreChatList').on('click', function(e) {
            e.preventDefault();
            currentChatListPage++;
            loadChatList();
        });

        $('#showLessChatList').on('click', function() {
            if (currentChatListPage > 1) {
                currentChatListPage--;
                loadChatList();
            }
        });


    });
</script>