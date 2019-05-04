const IMAGE_FORMATS = {
  "png": "png",
  "jpeg": "jpeg",
  "jpg": "jpg"
};

const VIDEO_FORMATS = {
  "mp4": "mp4",
  "webm": "webm"
};

const AUDIO_FORMATS = {
  "mp3": "mp3"
};

const ANIME = {
  "gif": "gif"
};

let parent;

app = {};

window.addEventListener('load', setUpDrawer);
window.addEventListener('resize', setUpDrawer);

function setUpDrawer() {
  let drawer = document.querySelector(".drawer-container");
  let mainWidth = document.querySelector('main').offsetWidth;
  let contents = document.getElementById("content-container");

  //keep the navbar opened when viewport is big enough to contain it and closed when small
  if (window.innerWidth > 1293) {  //NOTE: used in media query as well
    if (!drawer.classList.contains("persistent")) {  //only add when not there
      drawer.classList.add("persistent");
      drawer.classList.remove("drawer-hidden");
      contents.style.marginLeft = '240px';
    }
  } else {
    drawer.classList.remove("persistent");
    drawer.classList.add("drawer-hidden");
    contents.style.marginLeft = "0";
  }
}

let hamburger = document.querySelector(".hamburger");
hamburger.addEventListener('click', function (e) {
  let drawer = document.querySelector(".drawer-container");
  let contents = document.getElementById("content-container");

  //on wide viewport and with drawer open, close drawer and reset margin left
  if (drawer.classList.contains("persistent")) {
    if (contents.style.marginLeft == '240px') {
      drawer.classList.toggle("drawer-hidden");
      contents.style.marginLeft = '0';
    } else {
      drawer.classList.toggle("drawer-hidden");
      contents.style.marginLeft = '240px';
    }
  }
  //on smaller viewport, let drawer float on top and prevent content from being pushed to the right
  else {
    contents.style.marginLeft = '0';
    //drawer.style.top = "0";
    drawer.classList.toggle("drawer-hidden");
  }
  e.stopPropagation();
});


let mobileSearchBar = document.querySelector("#mobile-search");
let searchIcon = document.querySelector(".mobile-search-icon");
searchIcon.addEventListener("click", function (e) {
  mobileSearchBar.style.display = "flex";
});

let closeSearchIcon = document.querySelector("#close-search");
closeSearchIcon.addEventListener("click", function (e) {
  mobileSearchBar.style.display = "";
});

let closeModal = function (element, cssclass) {
  element.classList.remove(cssclass);

  //reset body to scrollable
  let body = document.body;
  body.classList.toggle("noscroll");

  //remove window event listener
  window.removeEventListener("mouseup", mouseUpEvent);
};

let removeChildNodes = function (parentNode) {
  let childrenLength = parentNode.children.length;

  //remove children expect for the first one
  while (childrenLength > 1) {
    parentNode.removeChild(parentNode.children[1]);
    childrenLength--;
  }
};

let createMediaFormatElement = function (attribute, text) {
  //create an option element, add text and attribute to it. Append the element to the DOM

  let option = document.createElement("OPTION");
  let textNode = document.createTextNode(text);
  option.append(textNode);
  option.setAttribute("value", attribute);

  let mediaFormat = document.querySelector("#media-format");
  mediaFormat.appendChild(option);
};

let formats = function (obj) {
  let mediaFormat = document.querySelector("#media-format");

  //remove children
  removeChildNodes(mediaFormat);

  //create element
  for (let key in obj) {
    createMediaFormatElement(key, obj[key]);
  }
};

let media = document.querySelector("#media-type");
if (media) {
  media.addEventListener("change", function (event) {
    let value = event.target.value;
    if (value == "video") {
      formats(VIDEO_FORMATS);
    }
    else if (value == "audio") {
      formats(AUDIO_FORMATS);
    }
    else if (value == "image") {
      formats(IMAGE_FORMATS);
    }
    else {
      formats(ANIME);
    }
  });
}

let adjustLeftPosition = function () {
  let metubeHeaderWidth = document.querySelector(".metube-header-wrapper").offsetWidth;

  //we want the element to always be to the left of the signedin Icon
  let signedInIconWidth = document.querySelector(".metube-signin").offsetWidth; //change .metube-signin to .signedin when we have used PHP to configure signin

  let signedInWrapper = document.querySelector(".signedin-wrapper");
  let signedInWrapperWidth = signedInWrapper.offsetWidth;

  //adjust left position
  let littleOffset = 6;
  let left = (metubeHeaderWidth - (signedInIconWidth + littleOffset)) - signedInWrapperWidth;

  if (left > 0) signedInWrapper.style.left = left + "px";
  else signedInWrapper.style.left = 0 + "px";
};

window.addEventListener("load", adjustLeftPosition);
window.addEventListener("resize", adjustLeftPosition);

let accountOptions = document.querySelector(".signedin-wrapper");
//Change this to the proper css class after PHP is linked
let accountOptionsButton = document.querySelector(".signedin");
accountOptionsButton.addEventListener("click", function (e) {
  accountOptions.classList.add("show");

  //prevent body from srolling
  let body = document.body;
  body.classList.toggle("noscroll");

  parent = accountOptions;
  addWindowListener();
});

function addWindowListener() {
  window.addEventListener("mouseup", mouseUpEvent);
}

//event listener cannot be removed without a handler, we want to remove the window listener after we are done with it
let mouseUpEvent = function () {
  //if the target's parent is upload. in other words, if parent has target as a child close upload
  if (!(parent.contains(event.target))) closeModal(parent, "show");
};


let setUpMessaging = function () {
  let littleOffset = 8;
  let messageContainer = document.querySelector(".message-wrapper");
  //console.log(messageContainer.offsetWidth);
  let metubeHeaderWidth = document.querySelector(".metube-header-wrapper").offsetWidth;

  //get the widths the elements in the header has taken on the screen
  let messageIconWidth = document.querySelector(".chat").offsetWidth;
  let signedInIconWidth = document.querySelector(".metube-signin").offsetWidth; //this or sigin image. NOTE: USE UNARY OPERATOR TO GET EITHER

  //we want the element to always be to the left of the message
  let widthFromRightToMessageIcon = signedInIconWidth + messageIconWidth;
  let messageContainerWidth;
  let left;

  /* first, set the width of the container, with display:none; the with can't be calculated
     and we need this to start adjustments
  */

  //
  if (metubeHeaderWidth < 450) {
    messageContainer.style.maxWidth = 385 + "px";
    messageContainer.style.top = 40 + "px";
    messageContainerWidth = messageContainer.offsetWidth;
    messageContainer.style.left = 0 + "px";
  }
  else {
    //reduce the width of the container as the screen becomes smaller. subtracting current screen width from the total width of elements from right to message icon, gives just enough width for message wrapper and not cover the users entire screen.

    messageContainerWidth = messageContainer.offsetWidth;
    //console.log(messageContainerWidth);
    //reset width back to default
    if (messageContainerWidth == 385) { messageContainerWidth = 450; }
    messageContainer.style.top = 8 + "px";

    //position to the left of message icon
    left = metubeHeaderWidth - (widthFromRightToMessageIcon + messageContainerWidth + littleOffset);
    //console.log(left);
    if (left < 0) {
      if (metubeHeaderWidth < 537) messageContainer.style.top = 40 + "px";
      messageContainer.style.left = 0 + "px";
      //start reducing the width of messageContainer
      messageContainer.style.maxWidth = (messageContainerWidth - 1) + "px";

    } else {
      messageContainer.style.left = left + "px";
    }
  }
};

let messageIcon = document.querySelector(".chat");
messageIcon.addEventListener("click", function () {
  let messageContainer = document.querySelector(".message-wrapper");
  //displaying before calling setUpMessaging enables width of messageContainer to be determined
  messageContainer.classList.add("show");
  setUpMessaging();

  //prevent body from srolling
  let body = document.body;
  body.classList.toggle("noscroll");

  parent = messageContainer;
  addWindowListener();
});

window.addEventListener("load", setUpMessaging);
window.addEventListener("resize", setUpMessaging);

let channelLeftArrow = document.querySelector(".nav-icon-left");
let channelRightArrow = document.querySelector(".nav-icon-right");
let channelNavItems = document.querySelectorAll(".channel-navs-item");
let slider = document.querySelector("#channel-navs-content");

index = 0;
leftSum = 0;
if (channelLeftArrow) {
  channelLeftArrow.addEventListener("click", function () {
    if (index < 6) {
      left = channelNavItems[index].offsetWidth;
      leftSum += left;
      slider.style.left = - leftSum + "px";
      index += 1;
    } else {
      return;
    }
  });
}
if (channelRightArrow) {
  channelRightArrow.addEventListener("click", function () {
    if (index > 0) {
      left = channelNavItems[index - 1].offsetWidth;
      leftSum -= left;
      slider.style.left = - leftSum + "px";
      index -= 1;
    } else {
      return;
    }
  });
}


let publicComment = document.querySelector("#public-comment-text");
let commentBtns = document.querySelector(".comment-btns-wrapper");
let commentWrapper = document.querySelector(".add-comment-wrapper");
let submitBtn = document.querySelector("#submit-public-comment");

//open the cancel and comment buttons and disable the comment button
if (publicComment) {
  publicComment.addEventListener("click", function () {
    commentWrapper.classList.toggle("active");
    commentBtns.style.display = "flex";
    submitBtn.disabled = true;
  });

  //if there is a value in the input field, enable comment button else disable it
  publicComment.addEventListener("input", function () {
    if (this.value) {
      submitBtn.disabled = false;
    } else {
      submitBtn.disabled = true;
    }
  });
}



//cancel posting a public comment
let cancelComment = document.querySelector("#cancel-public-comment");
if (cancelComment) {
  cancelComment.addEventListener("click", function (e) {
    e.preventDefault();
    commentBtns.style.display = "none";
    commentWrapper.classList.toggle("active");
  });
}



let openReplyBox = event => {
  //only open the replybox that is in the tree where the event originated
  if (event.target.classList.contains("reply-btn")) {
    let commentReview = event.target.parentNode.parentNode;
    //NOTE: change nextElementSibling to nextSibling when serving content dynamically
    let replyBox = commentReview.nextElementSibling;
    replyBox.style.display = "flex";
  }
};

let commentReviews = document.querySelectorAll(".comment-review");
for (let i = 0; i < commentReviews.length; i++) {
  //we can go deep into the dom tree by attaching a listener to a specific element
  commentReviews[i].addEventListener("click", openReplyBox);
}

let submitReplyBtns = document.querySelectorAll(".submit-reply");
for (let submitReply of submitReplyBtns) {
  submitReply.disabled = true;
}


let cancelTheReply = event => {
  event.preventDefault(); //prevent the button from doing its default task - submitting
  let replyBox = event.target.parentNode.parentNode.parentNode;
  replyBox.style.display = "none";
};

let cancelReplies = document.querySelectorAll(".cancel-reply");
for (let cancelReply of cancelReplies) {
  cancelReply.addEventListener("click", cancelTheReply);
}

let replyBtnOnOff = event => {
  //NOTE: replace nextElementSibling and lastElementChild with nextSibling and lastChild when serving dynamically
  let replyBtn = event.target.parentNode.parentNode.lastElementChild.lastElementChild;
  if (event.target.value) {
    replyBtn.disabled = false;
  } else {
    replyBtn.disabled = true;
  }
};

let replyInputs = document.querySelectorAll(".reply-public-comment");
for (let replyInput of replyInputs) {
  replyInput.addEventListener("input", replyBtnOnOff);
}

let userContactHeader = document.querySelector(".user-contacts-header");
if (userContactHeader) {
  let friendsContainer = document.querySelector(".friends-list-wrapper");
  let contactsContainer = document.querySelector(".contact-list-wrapper");
  let blockedContainer = document.querySelector(".blocked-list-wrapper");
  userContactHeader.addEventListener("click", event => {
    if (event.target.className == "contact") {
      friendsContainer.style.display = "none";
      blockedContainer.style.display = "none";
      contactsContainer.style.display = "block";
    }
    else if (event.target.className == "friend") {
      blockedContainer.style.display = "none";
      contactsContainer.style.display = "none";
      friendsContainer.style.display = "block";
    }
    else if (event.target.className == "blocked") {
      contactsContainer.style.display = "none";
      friendsContainer.style.display = "none";
      blockedContainer.style.display = "block";
    }
  });
}

let loggedin = function () {
  let signin = document.querySelector(".metube-signin");
  signin.style.display = "none";
  let signedin = document.querySelector(".signedin");
  signedin.style.display = "flex";
};

function downloadURI(event) {
  var uri = document.getElementById("linkpath").value;
  var name = document.getElementById("filename").value;
  var link = document.createElement("a");
  link.download = name;
  link.href = uri;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

let downloadBtn = document.querySelector("#download");
if (downloadBtn) {
  downloadBtn.addEventListener("click", downloadURI, false);
}

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
let showFilterOptions = function () {
  document.getElementById("myDropdown").classList.toggle("show");
};

let getSortData = function (element, className) {
  var data = element.getElementsByClassName(className)[0].innerHTML;
  if (className == "filesize" || className == "viewcount" || className == "rating") {
    return parseInt(data.match(/[0-9]+/g));
  } else if (className == "media-title") {
    return data.toLowerCase();
  } else if (className == "time") {
    return new Date(data.match(/[0-9]{2}\-[0-9]{2}\-[0-9]{4}/g))
  }
};

let sortData = function (className, leastToGreatest) {
  var list, i, switching, b, shouldSwitch;
  list = document.getElementById("mediacontent");
  switching = true;
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // start by saying: no switching is done:
    switching = false;
    b = list.getElementsByClassName("media-item-container");
    // Loop through all list-items:
    for (i = 0; i < (b.length - 1); i++) {
      // start by saying there should be no switching:
      shouldSwitch = false;
      /* check if the next item should
      switch place with the current item: */
      if (leastToGreatest && getSortData(b[i], className) > getSortData(b[i + 1], className)) {
        /* if next item is alphabetically lower than current item, mark as a switch and break the loop: */
        shouldSwitch = true;
        break;
      } else if (!leastToGreatest && getSortData(b[i], className) < getSortData(b[i + 1], className)) {
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark the switch as done: */
      b[i].parentNode.insertBefore(b[i + 1], b[i]);
      switching = true;
    }
  }
};

let sortListTitles = function () {
  sortData("media-title", true);
};

let sortByViewCount = function () {
  sortData('viewcount', false);
};

let sortByFileSize = function () {
  sortData("filesize", false);
};

let sortByRating = function () {
  sortData("rating", false);
};

let sortByDate = function () {
  sortData("time", false);
};

var types = [];
var fileTypeRegex = "";

var numbers = [];
var categoriesRegex = "";

let filterData = function () {
  var typePattern = new RegExp(fileTypeRegex, "g");
  var categoryPattern = new RegExp(categoriesRegex, "g");

  var list = list = document.getElementById("mediacontent");
  var b = list.getElementsByClassName("media-item-container");
  for (i = 0; i < b.length; i++) {
    var fileTypes = b[i].getElementsByClassName("filetype")[0].value;
    var categories = b[i].getElementsByClassName("categories")[0].value;

    if (fileTypes.match(typePattern) != null && categories.match(categoryPattern) != null) {
      b[i].style.display = "block";
    } else {
      b[i].style.display = "none";
    }
  }
};

let hideNonFileTypes = function (event) {
  var filetype = event.dataset.filetype;
  fileTypeRegex = "";
  if (filetype == "all") {
    types = [];
  } else {
    var index = types.indexOf(filetype);
    if (index == -1) {
      types.push(filetype);
    } else {
      types.splice(index, 1);
    }

    for (var i = 0; i < types.length; i++) {
      fileTypeRegex += types[i];
      if (i < types.length - 1) {
        fileTypeRegex += "|";
      }
    }
  }

  filterData();

  if (filetype == "all") {
    var buttons = document.getElementsByClassName("type-button");
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].classList.remove("selected-button");
    }
  } else {
    if (event.classList.contains("selected-button")) {
      event.classList.remove("selected-button");
    } else {
      event.classList.add("selected-button");
    }
  }
};


let hideNonCategories = function (event) {
  var number = event.dataset.number;
  categoriesRegex = "";
  if (number == 17) {
    numbers = [];
  } else {
    var index = numbers.indexOf(number);
    if (index == -1) {
      numbers.push(number);
    } else {
      numbers.splice(index, 1);
    }

    for (var i = 0; i < numbers.length; i++) {
      categoriesRegex += numbers[i];
      if (i < numbers.length - 1) {
        categoriesRegex += "|";
      }
    }
  }

  filterData();

  if (number == 17) {
    var buttons = document.getElementsByClassName("category-button");
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].classList.remove("selected-button");
    }
  } else {
    if (event.classList.contains("selected-button")) {
      event.classList.remove("selected-button");
    } else {
      event.classList.add("selected-button");
    }
  }
};

let increaseViewCount = (mediaId) => {
  let videoMedia = document.querySelector(".main-video");
  let audioMedia = document.querySelector(".main-audio");
  let imageMedia = document.querySelector(".media-img");
  if (videoMedia) {
    //videoMedia.addEventListener("playing", function() {
    //console.log(media.currentTime);

    $.post("templates.php", { type: "updateCount", media_id: mediaId }, function (response) {
      console.log(response);
    });
    //});
  }
  if (audioMedia) {
    //audioMedia.addEventListener("playing", function() {
    //console.log(media.currentTime);

    $.post("templates.php", { type: "updateCount", media_id: mediaId }, function (response) {
      console.log(response);
    });
    //});
  }
  if (imageMedia) {
    $.post("templates.php", { type: "updateCount", media_id: mediaId }, function (response) {
      console.log(response);
    });
  }

};


deny = [];
let viewAccess = document.querySelector("#search-to-block");
if (viewAccess) {
  viewAccess.addEventListener("click", function (event) {
    event.preventDefault();
    let containingDiv = document.querySelector(".people-result");
    //open modal
    let searchModal = document.querySelector("#block-a-user-container");
    searchModal.classList.add("show-block");

    //close modal
    let cancelBtn = document.querySelector("#cancel-block-user");
    cancelBtn.addEventListener("click", () => {
      searchModal.classList.remove("show-block");
      deny = [];
      //delete the elements
      while (containingDiv.firstChild) {
        containingDiv.removeChild(containingDiv.firstChild);
      }
      // reset the input form
      document.querySelector("#block-user-search-input").value = "";
    });

    let form = document.querySelector("#block-user-form");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      let inputValue = document.querySelector("#block-user-search-input").value;
      let actionUrl = form.action;

      //post request using JQUERY
      var posting = $.post(actionUrl, { type: "get_users", name: inputValue });

      //put the result in the div
      posting.done(function (response) {
        response = JSON.parse(response);
        let id, firstName, lastName;
        for (const person of response) {
          for (let key in person) {
            if (key == "user_id") id = person[key];
            else if (key == "first_name") firstName = person[key];
            else if (key == "last_name") lastName = person[key];
            else continue;
          }
          //append each person to the page
          create_element(containingDiv, id, firstName, lastName);
          //add listener to the block button
          let block = document.querySelectorAll(".block-user-btn");
          if (block) {
            for (let i = 0; i < block.length; i++) {
              block[i].addEventListener("click", addUserToDenyList);
            }
          }
        }
      });
    });

    //save the user details
    let saveBtn = document.querySelector("#save-block-user");
    saveBtn.addEventListener("click", () => {
      //create a hidden input element and append the list of users to deny, add to upload form
      let inputElement = document.createElement("input");
      inputElement.type = "hidden";
      inputElement.name = "deny";
      inputElement.value = JSON.stringify(deny);
      let uploadForm = document.querySelector("#uploadMedia");
      uploadForm.appendChild(inputElement);
      // close modal
      searchModal.classList.remove("show-block");
      //set deny list to empty
      deny = [];
      //delete the elements
      while (containingDiv.firstChild) {
        containingDiv.removeChild(containingDiv.firstChild);
      }
      document.querySelector("#block-user-search-input").value = "";
    });
  });
}

function addUserToDenyList(event) {
  let userID = event.target.value;
  deny.push(userID);
  event.target.disabled = true;
  return;
}

function create_element(containerDiv, id, first_name, last_name) {
  let personDiv = document.createElement("div");
  let personCardResultDiv = document.createElement("div");
  let friendContainerDiv = document.createElement("div");
  let friendImageContainerDiv = document.createElement("div");
  let friendImage = document.createElement("div");
  let icon = document.createElement("i");
  let friendNameDiv = document.createElement("div");
  let nameParagraph = document.createElement("p");
  let button = document.createElement("button");

  personDiv.className = "person";
  personCardResultDiv.className = "person-card-result";
  friendContainerDiv.className = "friend-container";
  friendImageContainerDiv.className = "friend-image-container";
  friendImage.className = "friend-image";
  icon.className = "fas fa-user";
  friendNameDiv.className = "friend-name";
  button.className = "btn btn-link block-user-btn";
  button.type = "button";
  button.id = "block-user-btn";
  button.name = "block-user";

  button.innerHTML = "block";
  button.value = id;
  nameParagraph.innerHTML = first_name + " " + last_name;

  personDiv.appendChild(personCardResultDiv);
  personCardResultDiv.appendChild(friendContainerDiv);
  friendContainerDiv.appendChild(friendImageContainerDiv);
  friendImageContainerDiv.appendChild(friendImage);
  friendImage.appendChild(icon);
  friendContainerDiv.appendChild(friendNameDiv);
  friendNameDiv.appendChild(nameParagraph);
  personCardResultDiv.appendChild(button);
  containerDiv.appendChild(personDiv);
  return;
}

let button = document.querySelectorAll(".user");
if (button) {
  for (let i = 0; i < button.length; i++) {
    button[i].addEventListener("click", processUnblockUser);
  }
}

function processUnblockUser(event) {
  let media_id = event.target.previousElementSibling.value;
  let user_id = event.target.previousElementSibling.previousElementSibling.value;

  let posting = $.post("../metube/templates.php", {type: "unblock_user", media_id: media_id, user_to_unblock: user_id});

      posting.done(function(response) {
        response = JSON.parse(response);
        if(response.message == 1) {
          //remove from the DOM
          let childParent = event.target.parentElement;
          let childGrandParent = childParent.parentElement;
          childGrandParent.removeChild(childParent);
          alert("user unblocked");
        } else if(response.message == 0) {
          alert("unblocking failed");
        } else alert(response);
   });
}

let commentButton = document.querySelectorAll(".comment-button");
if (commentButton) {
  for (let i = 0; i < commentButton.length; i++) {
    commentButton[i].addEventListener("click", addNewComment);
  }
}

function addNewComment(event) {
  let media_id = document.getElementById('hiddenmediaid').value;
  let user_id = document.getElementById('hiddenuserid').value;
  let comment = document.getElementById('public-comment-text').value;
  let commentInput = document.getElementById('public-comment-text');
  let commentThread = document.querySelector('.comment-thread');
  let allCommentWrapper = document.querySelector("#all-comments-wrapper");

  let posting = $.post("templates.php",
    { type: "add_comment", media_id: media_id, user_id: user_id, comment: comment });

  posting.done(function (response) {
    response = JSON.parse(response);
    if (response.message == 1) {
      //reset the comment input
      commentInput.value = " ";
      alert("comment added");
      //asynchronously refresh the comment section to display the comments
      $("#all-comments-wrapper").load(document.URL + ' #all-comments-wrapper');

    } else if (response.message == 0) {
      alert("adding failed");
    } else if (response.message == -1) {
      alert("log in to make a public comment");
    }
  });
}

let removeBtn = document.querySelectorAll(".remove-playlist");
if(removeBtn) {
  for (let i = 0; i < removeBtn.length; i++) {
    removeBtn[i].addEventListener("click", removePlaylist);
  }
}

function removePlaylist(event) {
  let media_id = event.target.name;
  let playlist_id = event.target.value;

  let posting = $.post("../templates.php",
    { type: "remove_playlist", media_id: media_id, playlist_id: playlist_id});

  posting.done(function (response) {
    response = JSON.parse(response);
    if (response.message == 1) {
      //remove from the DOM
      alert("removed from playlist");
      let containingDiv = document.querySelector(".multimedia-content");
      let node = event.target.parentNode.parentNode.parentNode.parentNode.parentNode;
      containingDiv.removeChild(node);
    } else if (response.message == 0) {
      alert("something failed");
    }
  });
}

let removeFavoriteBtn = document.querySelectorAll(".remove-favorite");
if(removeFavoriteBtn) {
  for (let i = 0; i < removeFavoriteBtn.length; i++) {
    removeFavoriteBtn[i].addEventListener("click", removeFavorite);
  }
}

function removeFavorite(event) {
  let media_id = event.target.name;
  let favorite_id = event.target.value;

  let posting = $.post("../templates.php",
    { type: "remove_favorite", media_id: media_id, favorite_id: favorite_id});

  posting.done(function (response) {
    response = JSON.parse(response);
    if (response.message == 1) {
      //remove from the DOM
      alert("removed from favorite");
      let containingDiv = document.querySelector(".multimedia-content");
      let node = event.target.parentNode.parentNode.parentNode.parentNode.parentNode;
      containingDiv.removeChild(node);
    } else if (response.message == 0) {
      alert("something failed");
    }
  });
}

function handleFavorite(logged_in_status, userID) {
  let mediaID = 0;
  let favoriteContainer = document.querySelector(".create-favorite-wrapper");
   //open Modal
  let openModal = function(event) {
    if(logged_in_status) {
      mediaID = event.target.name;
      favoriteContainer.classList.add("show-block");
    } else {
      alert("log in to add to favorite");
    }
  };

  let favoriteBtn = document.querySelectorAll(".add-favorite-btn");
  if(favoriteBtn) {
    for(let i = 0; i < favoriteBtn.length; i++){
      favoriteBtn[i].addEventListener("click", openModal);
    }
  }

  //close Modal
  let cancelBtn = document.querySelector(".cancel-favorite");
  if(cancelBtn) {
    cancelBtn.addEventListener("click", () => favoriteContainer.classList.remove("show-block"));
  }

  //save to favorite list
  let save_to_favorite = function(event) {
    let favorite_name = event.target.name;
    let favorite_id = event.target.value;
    var posting = $.post("../templates.php", {type: "save_to_favorite", favorite_name: favorite_name, media_id: mediaID, favorite_id: favorite_id, user_id: userID});

    posting.done(function(response) {
      response = JSON.parse(response);
      if(response.message == 1) {
        alert("added to", favorite_name);
      } else if(response.message == 2) {
          alert("media already in favorite");
      } else if(response.message == 0) {
        alert("something went wrong");
      }
    });
  };

  //get the users favourite list if any
  let savedFavorite = document.querySelectorAll(".favorite_check");
  if(savedFavorite) {
    for(let i = 0; i < savedFavorite.length; i++){
      savedFavorite[i].addEventListener("change", save_to_favorite);
    }
  }

  //create new favorite
  let favoriteForm = document.querySelector("#add_to_favorite");
  if(favoriteForm) {
    favoriteForm.addEventListener("submit", (event) => {
      event.preventDefault();
      let favoriteContainer = document.querySelector(".create-favorite-wrapper");
      let inputValue = document.querySelector("#favoritename").value;

      var posting = $.post("../templates.php", {type: "add_to_favorite", name: inputValue, media_id: mediaID, user_id: userID});

      posting.done(function(response) {
        response = JSON.parse(response);
        if(response.message == 1) {
          favoriteContainer.classList.remove("show-block");
          document.querySelector("#favoritename").value = "";
          alert("favorite created and media added");
        } else if(response.message == 0) {
          favoriteContainer.classList.remove("show-block");
          alert("something went wrong");
        } else if(response.message == -1) {
          alert("favorite name can't be empty");
        }
      });
    });
  }
}

let deleteCommentButton = document.querySelectorAll(".delete-comment-button");
if (deleteCommentButton) {
  for (let i = 0; i < deleteCommentButton.length; i++) {
    deleteCommentButton[i].addEventListener("click", deleteComment);
  }
}

function deleteComment(event) {
  let write_time = event.target.previousElementSibling.value;
  let user_id = event.target.previousElementSibling.previousElementSibling.value;
  let path = event.target.previousElementSibling.previousElementSibling.previousElementSibling.value;
  let posting = $.post(path+"templates.php", { type: "delete_comment", write_time: write_time, user_id: user_id });
    posting.done(function (response) {
      response = JSON.parse(response);
      if (response.message == 1) {
        //remove from the DOM
        let number = event.target.id.match(/[0-9]+/);
        let section = document.getElementById("commentid" + number);
        section.parentNode.removeChild(section);
      } else if (response.message == 0) {
        alert("deleting failed");
      }
    });
}

window.addEventListener("resize", setUpRecommendation);
window.addEventListener("load", setUpRecommendation);
function setUpRecommendation(event) {
  let mainWidth = document.querySelector("main").offsetWidth;
  let mediaRecommendation = document.querySelector(".media-recommendation-section");
  let secondaryMediaRecommendation = document.querySelector(".secondary-media-recommendation-section");
  if(mediaRecommendation || secondaryMediaRecommendation) {
    if(mainWidth > 1020) {
      let medias = mediaRecommendation.querySelectorAll(".media-item-container");
      if(medias) {
        let secondaryMediaRecommendation = document.querySelector(".secondary-media-recommendation-section");
        medias.forEach(function(mediaContainer) {
          secondaryMediaRecommendation.appendChild(document.adoptNode(mediaContainer));
        });
      }
    }
    else {
      let medias = secondaryMediaRecommendation.querySelectorAll(".media-item-container");
      if(medias) {
        let mediaRecommendation = document.querySelector(".media-recommendation-section");
        medias.forEach(function(mediaContainer) {
          mediaRecommendation.appendChild(document.adoptNode(mediaContainer));
        });
      }
    }
  }
}

let openForm = function() {
  document.getElementById("myForm").style.display = "block";
};

let closeForm = function() {
  document.getElementById("myForm").style.display = "none";
};

let openUserForm = function() {
  document.getElementById("adduserform").style.display = "block";
};

let closeUserForm = function() {
  document.getElementById("adduserform").style.display = "none";
};
