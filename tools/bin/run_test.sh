#!/usr/bin/env bash

function has_docker_compose() {
    local FILENAME="$( basename ${BASH_SOURCE[0]} )";
    local TEST_CONTAINER="${1}";
    command -v docker-compose >/dev/null 2>&1 || {
        echo "Docker compose not found, running ${TEST_CONTAINER} in vagrant box";
        vagrant up;
        vagrant ssh -c "bash ./tools/bin/${FILENAME} ${TEST_CONTAINER}";
        false;
    }

    true;
}

function run_test() {
    local TEST_CONTAINER="${1}";
    if has_docker_compose ${TEST_CONTAINER};
    then
        echo "Running docker container ${TEST_CONTAINER}";
        docker-compose -f docker-compose.dev.yml run "${TEST_CONTAINER}";
    fi
}

run_test "${1}";
