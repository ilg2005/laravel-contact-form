<template>
    <div>

        <div class="container">
            <h1 class="text-center mb-3">{{ msg }}</h1>
            <form method="post"
                  @submit.prevent="onFormSubmit"
                  ref="feedbackForm"
            >
                <div class="form-group">
                    <label>Имя</label>
                    <input
                        type="text"
                        class="form-control"
                        name="name"
                        id="name"
                        v-model="name"
                        required
                    >
                    <div class="error">
                    </div>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email"
                           class="form-control"
                           name="email"
                           id="email"
                           v-model="email"
                           required
                    >
                    <div class="error">
                    </div>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input
                        type="text"
                        class="form-control"
                        name="phone"
                        id="phone"
                        v-model="phone"
                        required
                    >
                    <div class="error">
                    </div>
                </div>
                <div class="form-group">
                    <label>Тема</label>
                    <input
                        type="text"
                        class="form-control"
                        name="subject"
                        id="subject"
                        v-model="subject"
                        required
                    >
                    <div class="error">
                    </div>
                </div>
                <div class="form-group">
                    <label>Сообщение</label>
                    <textarea
                        class="form-control"
                        name="message"
                        id="message"
                        rows="4"
                        v-model="message"
                        required
                    ></textarea>
                    <div class="error">
                    </div>
                </div>
                <input type="submit" name="send" value="Отправить" class="btn btn-primary btn-block">
            </form>
        </div>
    </div>
</template>

<script>

export default {
    name: 'FeedbackForm',
    props: {
        msg: String
    },
    data() {
        return {
            name: '',
            email: '',
            phone: '',
            subject: '',
            message: '',
        }
    },
    methods: {
       onFormSubmit() {
           let formData = new FormData();

           formData.append('name', this.name);
           formData.append('email', this.email);
           formData.append('phone', this.phone);
           formData.append('subject', this.subject);
           formData.append('message', this.message);

           axios.post('/', formData, {
               headers: {
                   "Access-Control-Allow-Origin": "*",
               }
           }).then((response) => {
               console.log(response.data);
               this.$refs.feedbackForm.reset();
           })
               .catch((error) => {
                   if (error.response) {
                       console.log(error.response.data);
                       console.log(error.response.status);
                       console.log(error.response.headers);
                   }
               })


       }
    }
}
</script>

<style scoped>

</style>
