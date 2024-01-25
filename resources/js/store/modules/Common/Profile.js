import {axiosGet} from "../../../common/Helper/AxiosHelper";

const state = {
    userprofile:{}
};
const getters = {
    getUserInformation: state => {
        return state.userprofile
    },
    getUserInformationId: state => {
        return state.userprofileid
    }
};
const actions = {
    getUserInformation({commit, state}) {
            axiosGet(`admin/authenticate-user`).then(({ data }) => {
                commit('PROFILE_PERSONAL_INFO', data)
            }).catch((error) => this.$toastr.e(error));
    },
    getUserInformationId({ commit, state }) {
        axiosGet(`admin/authenticate-user`)
            .then(({ data }) => {
                // Assuming `data` is an object with an `id` property
                const userId = data.id;

                // Commit the user ID to the Vuex store
                commit('SET_USER_ID', userId);
            })
            .catch((error) => this.$toastr.e(error));
    },
};
const mutations = {
    PROFILE_PERSONAL_INFO(state, data) {
        state.userprofile = data;
    },
    SET_USER_ID(state, data) {
        state.userprofileid = data;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}
