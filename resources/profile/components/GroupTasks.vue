<template>
  <section class="justify-content-center d-flex">
    <section class="text-right m-3 position-fixed fixed-bottom">
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored"
              @click="goto('chart/' + id)">
        چارت سازمانی
        <span class="material-icons">
            account_balance
          </span>
      </button>
    </section>


    <p class="mdl-color-text--grey-600 mdl-typography--display-2 text-center font-weight-bold font-vazir m-5 align-self-center justify-content-end"
       v-if="todolist.length == 0">

      هیج کاری برای انجام نیست
      <br>
      <span class="material-icons mdl-typography--display-1">
        work_off
      </span>
    </p>

    <section class="card todocard text-center m-3" v-else v-for="t in todolist">
      <img class="card-header" :src="getImageUrl(t.picture)" alt="بدون تصویر" v-if="t.picture">
      <section class="card-body">
        <section class="d-inline-flex align-items-center justify-content-center">
          <section class="d-inline-block">
            <h4 v-text="t.title"></h4>
            <span v-text="t.description"></span>

          </section>
        </section>

        <section class="text-right">
          <button class="mdl-button mdl-js-button">
          <span class="material-icons btn-card" data-toggle="modal" data-target="#myModal"
                @click="currentTodo = t">
            check
          </span>
            تحویل
          </button>

          <button class="mdl-button mdl-js-button">
            <a :href="getImageUrl(t.file)" v-if="t.file != null">
              <span class="material-icons">
                cloud_download
              </span>
              دانلد فایل پیوست
            </a>
          </button>
        </section>

      </section>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">تحویل کار</h4>
          </div>
          <div class="modal-body">
            <label>فایل تحویل (در صورت نیاز بارگذاری کنید و اجباری نیست) حد اکثر حجم فایل : ۱۰ مگ</label>
            <input class="form-control" type="file" @change="fileuploaded">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" @click="submit" data-dismiss="modal">تحویل</button>
          </div>
        </div>
      </div>
    </div>


  </section>
</template>

<script>
export default {
  props: ["id"],
  name: "GroupTasks",
  data() {
    return {
      todolist: [],
      picture: "",
      currentTodo: null,
      uploaded: null,
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      axios.get("api.php?a=todolist", {
        params: {
          group_id: parseInt(this.id)
        }
      }).then(value => {
        this.toastAll(value.data.payload);
        this.todolist = value.data.payload.values[0];
      }).catch(reason => {
        this.toastAll(reason.response.data.payload);
      })
    },
    submit() {
      var form = new FormData();
      form.append("id", this.currentTodo.id);
      if (this.uploaded != null) {
        form.append("answerFile", this.uploaded)
      }

      axios.post("api.php?a=answertodo", form, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(value => {
        this.toastAll(value.data.payload);
        this.init();
      }).catch(reason => {
        this.toastAll(reason.response.data.payload);
      })
    },
    fileuploaded(e) {
      this.uploaded = e.target.files[0]
    }
  }

}
</script>

<style scoped>
.todocard {
  border-radius: 20px;
  width: 40%;
  background: #f5f5f5;
}

.btn-card {
  cursor: pointer;
}

.btn-card:hover {
  color: #fbc531;
}
</style>