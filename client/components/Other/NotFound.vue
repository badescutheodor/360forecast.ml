<style lang="scss">
    .not-found {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);

        h1 {
            font-size: 36px;
            margin-top: 0;
        }

        p {
            font-size: 18px;
        }

        a {
            color: #c8e5ff;
            text-decoration: underline;
        }

        @media only screen and (max-width : 513px) {
            h1 {
                font-size: 25px;
            }

            p {
                font-size: 16px;
            }

        }
    }
</style>

<template>
    <div class="not-found">
        <h1>Page not found</h1>
        <p>Go to <router-link to="/">home</router-link> page.</p>
    </div>
</template>