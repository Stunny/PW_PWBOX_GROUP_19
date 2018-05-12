Vue.component('folder-item', {
  props: ['filename', 'extension', 'itemType'],
  data: function(){
    return {}
  },
  template: `
  <div class="four wide center aligned column">
    <div class="dashItem">
      <img class="ui image" src="media/{{itemType}}.png">
      <h4 class="ui grey header">
        {{filename}}.{{extension}}
      </h4>
    </div>
  </div>
  `
});

Vue.component('folder-item-row', {
  props: ['items'],
  data: function(){
    return {}
  },
  template: `
    <div class="row">
      <span>
        <folder-item
          v-for="item in items"
          :filename="item.filename"
          :extension="item.extension"
          :itemType="item.type"
          :key="item.filename+'.'+item.extension"
        >
        </folder-item>
      </span>
    </div>
  `
});


Vue.component('left-list-item', {
  props: ['name', 'children', 'folderID'],
  data: function(){
    return{};
  },
  template: `
  <div class="item">
    <div class="content">
      <a  class="item" :id="folderID">{{name}}</a>
      <span>
        <left-list-item
          v-for="item in children"
          :name="item.name"
          :key="'treeItem-'+item.name+'-'+item.id"
          :children="item.children"
          :folderID="'folder-'+item.id"
        >
        </left-list-item>
      </span> 
    </div>
  </div>
  `
});
