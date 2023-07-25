<template>
    <label>
        <input class="toggle-checkbox" type="checkbox" v-model="object[keyword]">
        <div class="toggle-slot"> <div class="toggle-button"></div> </div>
    </label>
</template>

<script>
export default {
    props : ['object', 'keyword']
}
</script>

<style lang="css" scoped>

label {
    direction: ltr;
    cursor: pointer;
}

.toggle-checkbox {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.toggle-slot {
    font-size: 10px;
    position: relative;
    height: 3em;
    width: 7em;
    border: 0px solid transparent;
    border-radius: 10em;
    background-color: #ccc;
    transition: background-color 250ms;
}

.toggle-checkbox:checked ~ .toggle-slot {
    background-color: var(--primary);
}

.toggle-button {
    transform: translate(3.9em, 0.25em);
    position: absolute;
    height: 2.5em;
    width: 2.5em;
    border-radius: 50%;
    background-color: var(--primary-lighten-4);
    box-shadow: inset 0px 0px 0px 0.75em var(--primary);
    transition: background-color 250ms, border-color 250ms, transform 500ms cubic-bezier(.26,2,.46,.71);
}

.toggle-checkbox:checked ~ .toggle-slot .toggle-button {
    background-color: var(--primary-darken-2);
    box-shadow: inset 0px 0px 0px 0.75em white;
    transform: translate(0.5em, 0.25em);
}

</style>
