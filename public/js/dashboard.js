
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

                centerContent.setElements(result);

            },
            404: function (res) {
                swal(
                    'Error 404',
                    'Folder not found',
                    'error'
                );
            }
        }
    });
}

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//--------------------------------HELPERS------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//

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

    $('#upload-files-modal').modal('show');
}

function showShareModal(){
    $('#share-folder-modal').modal('show');
}

function showFolderModal(){
    $('#new-folder-modal').modal('show');
}

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//--------------------------------ITEM ACTIONS-------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//

function createNewFolder(name){

  if(name.includes("/")){
      swal(
          'Oops...',
          'Folder name can not contain character "/"',
          'error'
      );
      return;
  }

  console.log("Crear nueva carpeta dentro de la carpeta con id: "+currentFolderId.replace(/folder-/, ""));

  $.ajax({
     url: '/api/user/'+userId+'/folder/',
     async: true,
     method: 'post',
     data:{
       folderName: name,
       parent: currentFolderId.replace(/folder-/, "")
     },
     statusCode:{
       200: function () {
           loadDashboardContent();
       },
       409: function () {
           swal(
               'Error 409: Already exists',
               'There is already a folder with this name on the current path',
               'error'
           );
       },
       401: function(){
           swal(
               'Error 401: Unauthorised',
               'You don\' have permission to create new folders here',
               'error'
           );
       }
     }
  });

}

function shareFolder(email, role){
    if(email.match(/^\s+$/) || !email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)){
        swal(
            'Oops',
            'Enter a valid email to share with',
            'error'
        );
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
               swal(
                   'Success',
                   'Folder shared successfully',
                   'success'
               );
           },
           404: function (res) {
               swal(
                   'Error 404',
                   'Folder not found',
                   'error'
               );
           },
           401: function (res) {
               swal(
                   'Error 401',
                   'Unauthorised',
                   'error'
               );
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

function deleteFolder(folderId) {

    selectedFolderId = folderId;
    $("#deleteFolderModal").modal("show");
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
                swal(
                    'Success',
                    'File renamed successfully: ',
                    'success'
                );
                loadCenterContent();
            },
            404: function (res) {
                swal(
                    'Error 404',
                    'File not found',
                    'error'
                );
            },
            401: function (res) {
                swal(
                    'Error 401',
                    'Unauthorised',
                    'error'
                );
            }
        }
    });
}

function requestRenameFolder(newName){
    console.log(selectedFolderId+"'s new name is"+newName);

    $.ajax({
        url: '/api/user/'+userId+'/folder/'+selectedFolderId.replace("folder-",""),
        async: true,
        method: 'post',
        data:{
            foldername: newName
        },
        statusCode:{
            200: function (res) {
                loadDashboardContent();
            },
            404: function (res) {
                swal(
                    'Error 404',
                    'Folder not found',
                    'error'
                );
            },
            401: function (res) {
                swal(
                    'Error 401',
                    'Unauthorised',
                    'error'
                );
            }
        }
    });
}

function deleteFileItem(fileId){
    selectedFileId = fileId;

    $.ajax({
        url: '/api/user/'+userId+'/folder/'+currentFolderId.replace("folder-","")+'/file/'+selectedFileId.replace("file-", ""),
        async: true,
        method: 'delete',
        statusCode:{
            200: function (res) {
                swal(
                    'Success',
                    'File deleted successfully: ',
                    'success'
                );
                loadCenterContent();
            },
            404: function (res) {
                swal(
                    'Error 404',
                    'File not found',
                    'error'
                );
            },
            401: function (res) {
                swal(
                    'Error 401',
                    'Unauthorised',
                    'error'
                );
            }
        }
    });

}

function requestDeleteFolder(){



    $.ajax({
        url: '/api/user/'+userId+'/folder/'+selectedFolderId.replace("folder-",""),
        async: true,
        method: 'delete',
        statusCode:{
            200: function (res) {
                swal(
                    'Deleted!',
                    'Folder deleted successfully',
                    'success'
                );
                loadCenterContent();
            },
            404: function (res) {
                swal(
                    'Error 404',
                    'Folder not found',
                    'error'
                );
            },
            401: function (res) {
                swal(
                    'Error 401',
                    'Unauthorised',
                    'error'
                );
            },
            400: function(res){
                swal(
                    'Could not delete folder',
                    'Folder is not empty so you can not delete it',
                    'error'
                );
            }
        }
    });
}

function downloadFile(fileId){
    window.open('/api/user/'+userId+'/folder/'+currentFolderId.replace("folder-","")+'/file/'+fileId.replace("file-", "")+'/download', '_blank');
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
  el: '#contentGrid',
  template: `
    <div class="ui grid container" id="itemGrid">
      <folder-item
        v-for="item in items"
        :key="elmtKey(item)"
        :filename="item.filename"
        :itemType="item.type"
        :data-id="item.type+'-'+item.id"
        :id="item.type+'-'+item.id"
      >
      </folder-item>
    </div>
  `,
  data: {
    items: []
  },
  methods:{
    setElements: function(items){
      this.items = items;
    },
    elmtKey: function(itemObj){

        return itemObj.type+'-'+itemObj.filename;
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

      window.location.href = '/profile';
    }
  }
});

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//-------------------------------DROPZONE------------------------------------------------//
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

$(document).on('dblclick', '.file.dashItem.three.wide.column', (event)=>{
    currentFolderId = $(event.target).parent().attr('id');
    pathTitle.setPath(getFolderBreadcrumb(currentFolderId));
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
        shareFolder($("input#shareEmail").val(), $("input#shareRole").prop("checked")? "admin":"read");
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

$("#deleteFolderModal").modal({
    onApprove: function () {
        requestDeleteFolder(selectedFolderId);
    }
});

//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//-------------------------------INICIAR DASHBOARD---------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------//
loadDashboardContent();