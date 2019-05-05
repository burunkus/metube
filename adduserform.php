<div class="form-popup" id="adduserform">
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-container" method="post">
    <h1>Add Member</h1>
    <label for="newgroupname">
      <b>Username/email</b>
    </label>
    <input type="text" placeholder="Enter Username or Email" name="newuserinfo" required>
    <button type="submit" class="btn">Add</button>
    <button type="button" class="btn cancel" onclick="closeUserForm()">Close</button>
    <input type="hidden" name="groupid" value="<?php echo $groupID;?>">
    <input type="hidden" name="groupname" value="<?php echo $title;?>">
  </form>
</div>
