Vue.component('folder-item', {
  props: ['filename', 'itemType', 'dataid'],
  data: function(){
    return {}
  },
  template: `
    <div class="file dashItem three wide column" :data-id="dataid">
      <img class="ui image" v-bind:src="'media/'+itemType+'.png'">
      <h4 class="ui grey header">
        {{filename}}
      </h4>
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
