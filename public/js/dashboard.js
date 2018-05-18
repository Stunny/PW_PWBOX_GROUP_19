
var dashLeftTree, pathTitle, centerContent, leftNav, filesDropzone;
var userId, rootFolderId, currentFolderId, selectedFileId, selectedFolderId, currentCenterRows;

function loadDashboardContent(){
    var get = $.ajax({
        async : true,
        type : 'get',
        url: '/api/user/'+userId+'/folder/'+rootFolderId+'/tree'
    });

    get.done((elmts, textStatus)=>{
        rootFolderTree = elmts.res;
        dashLeftTree.setTree([elmts.res]);
    });

}


function loadCenterContent(){
    var get = $.ajax({
        async : true,
        type : 'get',
        url: '/api/user/'+userId+'/folder/'+currentFolderId.replace(/folder-/, "")+'/content',
        statusCode: {
            200: function(elmts){
                let result = Object.values(elmts.res);
                let qRows = Math.ceil(result.length/4);

                currentCenterRows = [];

                let i, j, chunk = 4;
                for (i = 0, j = result.length; i < j; i += chunk){
                    let rowObject = {};
                    rowObject.items = result.slice(i, i+chunk);
                    currentCenterRows.push(rowObject);
                }
                centerContent.setRows(currentCenterRows);

            },
            404: function () {
                alert("Folder not found");
            }
        }
    });
}

function getParentId(id){
  return $("#"+id).parent().parent().parent().prev().attr('id');
}

function getFolderBreadcrumb(id){
  let bc = $("#"+id).text()+'-';
  let folderID = id;
  let parentId;

  while(getParentId(folderID) != undefined){
    parentId = getParentId(folderID);
    bc += $("#"+parentId).text()+'-';
    folderID = parentId;
  }

  bc.replace(/-$/, '');
  return bc.split('-').reverse().splice(1, bc.length-1);
}

function showFileModal(){

    $('.fullscreen.modal').modal('show');
}

function showShareModal(){
    $('.small.modal').modal('show');
}

function showFolderModal(){
    $('.tiny.modal').modal('show');
}

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//--------------------------------ITEM ACTIONS-------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//

function createNewFolder(name){

  if(name.includes("/")){
      alert("Folder name can't contain character '/'");
      return;
  }

  $.ajax({
     url: '/api/user/'+userId+'/folder/',
     async: true,
     method: 'post',
     data:{
       folderName: name,
       path: pathTitle.toString()+'/'+name
     },
     statusCode:{
       200: function () {
           loadDashboardContent();
       }
     }
  });

}

function shareFolder(email, role){
    if(email.match(/^\s+$/) || !email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)){
        alert("Invalid email to share");
        return;
    }

    $.ajax({
       url: '/api/user/'+userId+'/folder/'+currentFolderId.replace(/folder-/, "")+'/share/'+email,
       async: true,
       method: 'post',
       data:{
           role: role
       },
       statusCode:{
           200: function (res) {
                alert(res.msg);
           },
           404: function (res) {
               alert(res.msg);
           },
           401: function (res) {
               alert(res.msg);
           }
       }
    });
}

function renameFolder(folderId) {

    selectedFolderId = folderId;
    $("#renameFolderModal").modal("show");
}

function renameFileItem(fileId){
    selectedFileId = fileId;

    $("#renameFileModal").modal("show");
}

function requestRenameFileItem(newName) {

    console.log("Rename file with id: "+selectedFileId);
    $.ajax({
        url: '/api/user/'+userId+'/folder/'+currentFolderId.replace("folder-","")+'/file/'+selectedFileId.replace("file-", ""),
        async: true,
        method: 'post',
        data:{
            filename: newName
        },
        statusCode:{
            200: function (res) {
                alert(res.msg);
                loadCenterContent();
            },
            404: function (res) {
                alert("Error 404: "+res.msg);
            },
            401: function (res) {
                alert("Error 401: "+res.msg);
            }
        }
    });
}

function requestRenameFolder(newName){
    console.log(selectedFolderId+"'s new name is"+newName);
}

function deleteFileItem(fileId){
    selectedFileId = fileId;

    $.ajax({
        url: '/api/user/'+userId+'/folder/'+currentFolderId.replace("folder-","")+'/file/'+selectedFileId.replace("file-", ""),
        async: true,
        method: 'delete',
        statusCode:{
            200: function (res) {
                alert(res.msg);
                loadCenterContent();
            },
            404: function (res) {
                alert("Error 404: "+res.msg);
            },
            401: function (res) {
                alert("Error 401: "+res.msg);
            }
        }
    });

}

function deleteFolder(folderId) {

    selectedFolderId = folderId;
}

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//-------------------------------VARIABLES GLOBALES--------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//

userId = document.cookie.match(/user=[^;]+/)[0].split('=')[1];
rootFolderId = document.cookie.match(/rootFolderId=[^;]+/)[0].split('=')[1];
currentFolderId = 'folder-'+rootFolderId;

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//-------------------------------COMPONENTES VUE-----------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
dashLeftTree = new Vue({
  el: '#dashLeftTree',
  data: {
    tree: []
  },
  template: `
    <span>
      <left-list-item
        v-for="item in tree"
        :name="item.name"
        :children="item.children"
        :key="'treeItem-'+item.name+'-'+item.id"
        :folderID="'folder-'+item.id"
      >
      </left-list-item>
    </span>  
  `,
  methods: {
    setTree: function(arrayTree){
      this.tree = arrayTree;
      pathTitle.setPath([arrayTree[0].name]);
    }
  }
});

pathTitle = new Vue({
  el: '#pathTitle',
  template: `
  <div class="ui big centered breadcrumb" id="path">
    <a :class="path.length != 1?'section':'active section'">{{path[0]}}</a>
    <span v-for="(item, index) in path">
      <span v-if="index != 0">
      <i class="right chevron icon divider"></i>
      <a :class="index == path.length-1?'active section': 'section'">{{path[index]}}</a>
      </span>
    </span>
  </div>
  `,
  data: {
    path: []
  },
  methods: {
    setPath: function(path){
      this.path = path;

      loadCenterContent();
    },
    toString: function () {
        let res = "";
        for(let i = 0; i < this.path.length; i++){
          res += this.path[i]+'/';
        }
        return res.substring(0, res.length-1);
    }
  }
});

centerContent = new Vue({
  el: '#rows',
  template: `
    <span>
      <folder-item-row
        v-for="row in rows"
        :items="row.items"
        :key="rowkey(row.items)"
      >
      </folder-item-row>
    </span>
  `,
  data: {
    rows: []
  },
  methods:{
    setRows: function(rows){
      this.rows = rows;
    },
    rowkey: function(items){
        var key = "";
        for(item in items){
          key += item.filename + "-";
        }

        return key;
    }
  }
});

leftNav = new Vue({
  el: '#topSideList',
  data:{
    filesSelected: true,
    profileSelected: false,
    settingsSelected: false
  },
  methods:{
    tabProfile: function(){
      this.filesSelected = false;
      this.profileSelected = true;
      this.settingsSelected = false;

      window.location.href = '/profile';
    }
  }
});

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//-------------------------------DROPZONE--------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//

Dropzone.options.fileDropzone = {
    url: '/api/user/'+userId+'/folder/'+currentFolderId.replace(/folder-/, "")+'/file',
    method: 'post',
    uploadMultiple: true,
    createImageThumbnails: true,
    maxFiles: 5,
    maxFilesize: 5,
    clickable: true,
    autoProcessQueue: false,
    ignoreHiddenFiles: true,
    addRemoveLinks: true,
    acceptedFiles: 'image/jpg, image/jpeg, image/gif, image/png, application/pdf, .txt, .md',
    init: function () {
        filesDropzone = this;
        this.on("complete", (file)=>{
            filesDropzone.removeFile(file);
        });

        this.on("addedfile", (event)=>{
            this.options.url = '/api/user/'+userId+'/folder/'+currentFolderId.replace(/folder-/, "")+'/file';
        });

        this.on("complete", ()=>{
            loadCenterContent();
        });
    }
};

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//-------------------------------LISTENERS JQUERY----------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//

$("#userIcon").click((event)=>{
  leftNav.tabProfile();
});

$(document).on('click','a[id^="folder-"]',(event)=>{
  let id = event.target.id;
    currentFolderId = id;
  pathTitle.setPath(getFolderBreadcrumb(id));
});

$('#new-folder-modal').modal({
    onApprove: function () {
        let foldername = $('#newFolderName');
        createNewFolder(foldername.val());
        foldername.val("");
    },
    onDeny: function () {
        let foldername = $('#newFolderName');
        foldername.val("");
    },
    onHide: function () {
        let foldername = $('#newFolderName');
        foldername.val("");
    }
});

$('#upload-files-modal').modal({
    onApprove: function () {
        filesDropzone.processQueue();
    },
    onDeny: function () {
        filesDropzone.removeAllFiles(true);
    }
});

$("#share-folder-modal").modal({
    onApprove: function () {
        shareFolder($("input#shareEmail").val(), $("input#shareRole").checkbox("is checked")? "admnin":"read");
    },
    onDeny: function () {
        $("input#shareEmail").val("");
    },
    onHide: function () {
        $("input#shareEmail").val("");
    }
});

$("#renameFileModal").modal({
    onDeny: function () {
        $("input#renameFileName").val("");
    },
    onHide: function () {
        $("input#renameFileName").val("");
    },
    onApprove: function () {
        requestRenameFileItem($("input#renameFileName").val())
    }
});

$("#renameFolderModal").modal({
    onDeny: function () {
        $("input#newFolderName").val("");
    },
    onHide: function () {
        $("input#newFolderName").val("");
    },
    onApprove: function () {
        requestRenameFolder($("input#renameFolderName").val())
    }
});

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//-------------------------------INICIAR DASHBOARD---------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
loadDashboardContent();


