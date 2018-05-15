
var dashLeftTree, pathTitle, centerContent, leftNav;
var userId, rootFolderId;

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

function loadCenterContent(){
  //todo: cargar los contenidos de la carpeta para su visualizacion
  
}

function showFileModal(){

}

function createNewFolder(name){

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

function showFolderModal(){
    $('.tiny.modal').modal('show');
}
  //-------Inicializaciones de modulos Vue

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
    rowKey: function(items){
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

      changeToProfile();
    },
    tabSettings: function(){
      this.filesSelected = false;
      this.profileSelected = false;
      this.settingsSelected = true;

      changeToSettings();
    }
  }
});

//------------------------Scripts auxiliares de la pagina----------------------------------//

function changeToFiles(){
  var get = $.ajax({
    async : true,
    type : 'get',
    url: '/api/user/'+userId+'/folder/'+rootFolderId+'/tree'
  });

  get.done((elmts, textStatus)=>{
    console.log(JSON.stringify(elmts.res));
    rootFolderTree = elmts.res;
    dashLeftTree.setTree([elmts.res]);
  });
}

function changeToProfile(){
    window.location.href = "";
}

function changeToSettings(){
  window.location.href = "";
}

function loadDashboardContent(){
  var get = $.ajax({
    async : true,
    type : 'get',
    url: '/api/user/'+userId+'/folder/'+rootFolderId+'/tree'
  });

  get.done((elmts, textStatus)=>{
    console.log(JSON.stringify(elmts.res));
    rootFolderTree = elmts.res;
    dashLeftTree.setTree([elmts.res]);
  });
}

$("#userIcon").click((event)=>{
  leftNav.tabProfile();
});

userId = document.cookie.match(/user=[^;]+/)[0].split('=')[1]; 
rootFolderId = document.cookie.match(/rootFolderId=[^;]+/)[0].split('=')[1]
loadDashboardContent();

$(document).on('click','a[id^="folder-"]',(event)=>{
  let id = event.target.id,
      content = $('#'+id).text();
  //TODO: implementar la funcion que cambia el breadcrumb
  // y el contenido interno del dashCenter
  pathTitle.setPath(getFolderBreadcrumb(id));
});

$('#new-folder-modal').modal({
    onApprove: function () {
        let foldername = $('#newFolderName');
        createNewFolder(foldername.val());
        foldername.val("");
    }
});


