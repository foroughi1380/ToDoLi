<template>
  <section class="d-flex justify-content-center align-items-center">


    <vue2-org-tree
        :data="tree"
        :props="{label: 'name', children: 'employs', expand: 'expand'}"
        collapsable
        :label-width="90"
        label-class-name="org-tree-lableeeeeeee"
        :render-content="renderContent"
        @on-expand="onExpand"
        @on-node-click="onNodeClick"
        selected-class-name="selected-node"
        selected-key="selectedKey"
        class="bg-transparent d-inline-flex"
        style="direction: ltr"
    />

    <section class="justify-content-center aside" v-if="cnode != null">


      <section class="d-flex justify-content-center align-items-center">
        <p>
          <br>
          {{ cnode.name + " " + cnode.family }}
          ({{ cnode.email }})
        </p>
      </section>

      <section class="card todocard text-center m-3" v-for="t in todolist">
        <img class="card-header" :src="getImageUrl(t.picture)" alt="بدون تصویر" v-if="t.picture">
        <section class="card-body">
          <section class="d-inline-flex align-items-center justify-content-center">
            <section class="d-inline-block">
              <h4 v-text="t.title"></h4>
              <span v-text="t.description"></span>

            </section>
          </section>

          <section class="text-right">
            <span class="ml-3 material-icons btn-card" v-if="t.answered == 1">
              check
            </span>
            <button class="mdl-button mdl-js-button" v-else @click="initedit(t)" data-toggle="modal" data-target="#addeditmodal">
              ویرایش
            </button>

            <button class="mdl-button mdl-js-button" v-if="t.answered != 1" @click="deleteTodo(t)">
              <span class="text-danger">
                حذف
              </span>
            </button>

            <button class="mdl-button mdl-js-button" v-if="t.file != null">
              <a :href="getImageUrl(t.file)" >
              <span class="material-icons">
                cloud_download
              </span>
                دانلد فایل پیوست
              </a>
            </button>



            <button class="mdl-button mdl-js-button" v-if="t.answerFile != null">
              <a :href="getImageUrl(t.answerFile)" >
              <span class="material-icons">
                cloud_download
              </span>
                دانلد فایل جواب
              </a>
            </button>



          </section>

        </section>

      </section>


    </section>

    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect position-fixed m-3"
            style="left: 0;bottom: 0" data-toggle="modal" data-target="#addeditmodal" v-show="cnode != null"
            @click="editstatus = false">
      <i class="material-icons">add</i>
    </button>


    <div class="modal fade" id="addeditmodal" role="dialog">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">وظیفه</h4>
          </div>
          <div class="modal-body">

            <label>فایل تصویر (در صورت نیاز بارگذاری کنید و اجباری نیست) حد اکثر حجم فایل : ۱ مگ</label>
            <input class="form-control" type="file" @change="pictureUploaded">

            <label>فایل ضمیمه (در صورت نیاز بارگذاری کنید و اجباری نیست) حد اکثر حجم فایل : ۱۰ مگ</label>
            <input class="form-control" type="file" @change="fileUploaded">

            <label>تایتل</label>

            <input class="form-control" v-model="title">

            <label>توضیحات</label>
            <input class="form-control" v-model="description">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" @click="edit" v-if="editstatus" data-dismiss="modal" data-target="#addeditmodal">ویرایش</button>
            <button type="button" class="btn btn-default" @click="defini" v-else data-dismiss="modal" data-target="#addeditmodal">ایجاد</button>
          </div>
        </div>
      </div>
    </div>





    <button class="mdl-button mdl-js-button mdl-button--fab add-user mdl-button--colored" data-toggle="modal" data-target="#adduser">
      <i class="material-icons">person_add</i>
    </button>

    <div class="modal fade" id="adduser" role="dialog">
      <div class="modal-dialog modal-sm" >
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-right">اضافه کردن عضو</h4>
          </div>
          <div class="modal-body">

            <p class="text-right">ایمیل</p>

            <input class="form-control" v-model="email" type="email">

            <p class="text-right">صمت</p>
            <input class="form-control" v-model="semat">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" @click="adduser" data-dismiss="modal" data-target="#adduser">درخواست</button>
          </div>
        </div>
      </div>
    </div>

  </section>
</template>

<script>
export default {
  name: "Chart",
  props: ["id"],
  data: () => ({
    tree: {},
    cnode: null,
    todolist: [],
    title: null,
    description: null,
    picture: null,
    file: null,
    editstatus: false,
    editstatusTodo: null,
    email: null,
    semat: null,
  }),
  created() {
    this.init();
  },
  methods: {
    init() {
      axios.get("api.php?a=groupchart&id=" + this.id)
          .then(value => {
            this.toastAll(value.data.payload);
            this.tree = value.data.payload.values[0];
          }).catch(reason => {
        this.toastAll(reason.response.data.payload);
      })
    },
    fileUploaded(e) {
      this.file = e.target.files[0]
    },
    pictureUploaded(e) {
      this.picture = e.target.files[0]
    },
    defini() {
      var form = new FormData();
      form.append("group_id", this.id);
      form.append("user_id", this.cnode.id);

      if (this.title == null || this.title.trim() === "") {
        this.toast("لطفا تایتل را وارد کنید", "error")
        return;
      }
      form.append("title", this.title)

      if (this.description == null || this.description.trim() === "") {
        this.toast("لطفا توضیحات را وارد کنید", "error")
        return;
      }
      form.append("description", this.description)

      if (this.picture != null) {
        form.append("picture", this.picture)
      }

      if (this.file != null) {
        form.append("file", this.file)
      }

      axios.post("api.php?a=definisiontodo", form, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(value => {
        this.toastAll(value.data.payload);
        this.picture = this.file = this.title = this.description = null;
        this.loadTodo()
      }).catch(reason => {
        this.toastAll(reason.response.data.payload);
      })
    },
    edit() {
      var form = new FormData();
      form.append("id", this.editstatusTodo.id);

      if (this.title == null || this.title.trim() === "") {
        this.toast("لطفا تایتل را وارد کنید", "error")
        return;
      }
      form.append("title", this.title)

      if (this.description == null || this.description.trim() === "") {
        this.toast("لطفا توضیحات را وارد کنید", "error")
        return;
      }
      form.append("description", this.description)

      if (this.picture != null) {
        form.append("picture", this.picture)
      }

      if (this.file != null) {
        form.append("file", this.file)
      }

      axios.post("api.php?a=updatetodo", form, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(value => {
        this.toastAll(value.data.payload);
        this.picture = this.file = this.title = this.description = null;
        this.loadTodo()
      }).catch(reason => {
        this.toastAll(reason.response.data.payload);
      })
    },
    initedit(t){
      this.editstatus = true
      this.title = t.title;
      this.description = t.description;
      this.editstatusTodo = t;
    },
    deleteTodo(t){
      if (window.confirm("ایا مطمعین به حذف هستید")){
        axios.post("api.php?a=deletetodo&id=" + t.id).then(value => {
          this.toastAll(value.data.payload);
          this.loadTodo()
        }).catch(reason => {
          this.toastAll(reason.response.data.payload);
        })
      }
    },
    adduser(){
      if (this.semat == null || this.semat.trim() === "") {
        this.toast("لطفا صمت را وارد کنید", "error")
        return;
      }

      if (this.email == null || this.email.trim() === "" || !this.validateEmail(this.email) ) {
        this.toast("لطفا ایمیل را وارد کنید", "error")
        return;
      }

      axios.get("api.php?a=joinrequest", {
        params:{
          group_id : parseInt(this.id),
          title : this.semat,
          email : this.email
        }
      }).then(value => {
        this.toastAll(value.data.payload);
        this.semat = this.email;
      }).catch(reason => {
        this.toastAll(reason.response.data.payload);
      })
    },
    loadTodo(){
      if (this.cnode == null) return;

      axios.get("api.php?a=usertodolist", {params: {group_id: this.id, user_id: this.cnode.id}})
          .then(value => {
            this.toastAll(value.data.payload);
            this.todolist = value.data.payload.values[0]
          }).catch(reason => {
        this.toastAll(reason.response.data.payload);
      })
    },
    renderContent(h, data) {
      return data.name;
    },
    onExpand(e, data) {
      if ('expand' in data) {
        data.expand = !data.expand
        if (!data.expand && data.children) {
          this.collapse(data.children)
        }
      } else {
        this.$set(data, 'expand', true)
      }
    },
    onNodeClick(e, data) {
      var selected = document.querySelectorAll(".selected-node");
      for (const selectedElement of selected) {
        selectedElement.classList.remove("selected-node")
      }
      this.$set(data, 'selectedKey', !data['selectedKey']);
      if (this.cnode != null && this.cnode.id === data.id) {
        this.cnode = null
      } else {
        this.cnode = data;
      }
    }
  },
  watch: {
    cnode() {
      this.loadTodo()
    }
  }

}
</script>

<style scoped>
.aside {
  max-height: 100vh;
  min-height: 100vh;
  background: rgba(224, 224, 224, 0.7);
  width: 30%;
  position: fixed;
  left: 0;
  top: 0px;
  padding-top: 90px;
  overflow-y: scroll;
}

.add-user{
  position: fixed;
  bottom: 0;
  right: 0;
  margin: 1rem;
}
</style>