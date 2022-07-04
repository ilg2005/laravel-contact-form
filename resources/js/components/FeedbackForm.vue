<template>
    <div>

        <div class="container">
            <h1 class="text-center mb-3">{{ msg }}</h1>
            <form method="post" action="/">
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <div class="error">
                    </div>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-control" name="email" id="email">
                    <div class="error">
                    </div>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" class="form-control" name="phone" id="phone">
                    <div class="error">
                    </div>
                </div>
                <div class="form-group">
                    <label>Тема</label>
                    <input type="text" class="form-control" name="subject"
                           id="subject">
                    <div class="error">
                    </div>
                </div>
                <div class="form-group">
                    <label>Сообщение</label>
                    <textarea class="form-control" name="message" id="message"
                              rows="4"></textarea>
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
    }
}
</script>

<style scoped>

</style>
