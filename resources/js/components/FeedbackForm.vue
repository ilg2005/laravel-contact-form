<template>
    <div>

        <v-container>
            <h1 class="text-center mb-3">{{ msg }}</h1>

            <div v-if="errors">
                <div v-for="(field, k) in errors" :key="k"
                     class="error text-center">
                    <p v-for="(error, j) in field" :key="j">
                        {{ error.toString().replace(/[]"/, '') }}
                    </p>
                </div>
            </div>

            <v-row>
                <v-col cols="4" class="mx-auto">
                    <v-form method="post"
                            ref="form"
                            v-model="valid"
                            lazy-validation
                    >
                        <div class="form-group">
                            <label>Имя</label>
                            <v-text-field
                                solo
                                name="name"
                                id="name"
                                v-model.trim="fields.name"
                                :rules="rules.name"
                                required
                            />
                        </div>

                        <div class="form-group">
                            <label>E-mail</label>
                            <v-text-field
                                solo
                                type="email"
                                name="email"
                                id="email"
                                v-model.trim="fields.email"
                                :rules="rules.email"
                                required
                            />

                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <v-text-field
                                solo
                                name="phone"
                                id="phone"
                                v-model.trim="fields.phone"
                                :rules="rules.phone"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label>Тема</label>
                            <v-text-field
                                solo
                                name="subject"
                                id="subject"
                                v-model.trim="fields.subject"
                                :rules="rules.subject"
                                required
                            />
                            <div class="error">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Сообщение</label>
                            <v-textarea
                                solo
                                name="message"
                                id="message"
                                rows="4"
                                v-model.trim="fields.message"
                                :rules="rules.message"
                                required
                            ></v-textarea>
                            <div class="error">
                            </div>
                        </div>
                        <v-btn
                            block
                            type="submit"
                            :disabled="!valid || isSending"
                            @click.prevent="onFormSubmit"
                        >
                            {{ isSending ? 'Отправляется...' : 'Отправить' }}
                        </v-btn>

                        <Notification
                            v-if="notification.show"
                            :notification-msg="notification.msg"
                            :notification-type="notification.type"
                        />
                    </v-form>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>

import Notification from "./Notification";

export default {
    name: 'FeedbackForm',
    components: {Notification},
    props: {
        msg: String
    },
    data() {
        return {
            valid: true,
            errors: [],
            isSending: false,
            fields: {
                name: '',
                email: '',
                phone: '',
                subject: '',
                message: '',
            },
            rules: {
                name: [
                    v => !!v || 'Имя обязательно',
                    v => (v && v.length <= 10) || 'Имя должно быть менее 10 символов',
                ],
                email: [
                    v => !!v || 'Нужно ввести E-mail',
                    v => /.+@.+\..+/.test(v) || 'Введите валидный E-mail',
                ],
                phone: [
                    v => !!v || 'Нужно ввести телефон',
                    v => /\d+/.test(v) || 'Только цифры!',
                    v => (v && v.length <= 11) || 'Не более 11 цифр',
                ],
                subject: [
                    v => !!v || 'Тема обязательна',
                ],
                message: [
                    v => !!v || 'Сообщение обязательно',
                    v => (v && v.length <= 500) || 'Не более 500 символов'
                ],
            },
            notification: {
                show: false,
                msg: '',
                type: ''
            },
        }
    },
    watch: {
        isSending: nv => nv,
    },
    methods: {
        onFormSubmit() {

            if (this.$refs.form.validate()) {
                this.isSending = true;
                const formData = new FormData();

                formData.append('name', this.fields.name);
                formData.append('email', this.fields.email);
                formData.append('phone', this.fields.phone);
                formData.append('subject', this.fields.subject);
                formData.append('message', this.fields.message);

                axios.post('/', formData, {
                    headers: {
                        "Access-Control-Allow-Origin": "*",
                    }
                }).then((_response) => {
                    this.notification.msg = 'Отправлено успешно!';
                    this.notification.type = 'success';
                    this.notification.show = true;
                    this.$refs.form.reset();

                })
                    .catch((error) => {
                        if (error.response) {
                            this.notification.msg = `Ошибка ${error.response.status}: отправить не удалось!`;
                            this.notification.type = 'danger';
                            this.notification.show = true;
                            if (error.response.status === 422) {
                                this.errors.push(error.response.data.errors);
                            }
                            console.log(error.response.data);
                        }
                    })
                    .finally(() => {
                        this.isSending = false;
                        setTimeout(() => this.notification.show = false, 3000);
                    })

            }
        }
    }
}
</script>

<style scoped>

</style>
